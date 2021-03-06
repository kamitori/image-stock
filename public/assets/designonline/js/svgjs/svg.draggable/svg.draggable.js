// svg.draggable.js 0.1.0 - Copyright (c) 2014 Wout Fierens - Licensed under the MIT license
// extended by Florian Loch
;(function() {

  SVG.extend(SVG.Element, {
    // Make element draggable
    // Constraint might be a object (as described in readme.md) or a function in the form "function (x, y)" that gets called before every move.
    // The function can return a boolean or a object of the form {x, y}, to which the element will be moved. "False" skips moving, true moves to raw x, y.
    draggable: function(constraint) {
      var start, drag, end
        , element = this
        , parent  = this.parent._parent(SVG.Nested) || this._parent(SVG.Doc)

      /* remove draggable if already present */
      if (typeof this.fixed === 'function')
        this.fixed()

      /* ensure constraint object */
      constraint = constraint || {}

      /* start dragging */
      start = function(event) {
        event = event || window.event

        /* invoke any callbacks */
        if (element.beforedrag) {
          if( !element.beforedrag(event) ) {
            return false;
          }
        }

        /* get element bounding box */
        var box = element.bbox()

        if (element instanceof SVG.G) {
          box.x = element.x()
          box.y = element.y()

        } else if (element instanceof SVG.Nested) {
          box = {
            x:      element.x()
          , y:      element.y()
          , width:  element.width()
          , height: element.height()
          }
        }

        /* store event */
        element.startEvent = event

        /* store start position */
        element.startPosition = {
          x:        box.x
        , y:        box.y
        , width:    box.width
        , height:   box.height
        , zoom:     parent.viewbox().zoom
        , rotation: element.transform('rotation') * Math.PI / 180
        }

        /* add while and end events to window */
        SVG.on(window, 'mousemove', drag)
        SVG.on(window, 'mouseup',   end)

        /* invoke any callbacks */
        if (element.dragstart)
          element.dragstart({ x: 0, y: 0, zoom: element.startPosition.zoom }, event)

        /* prevent selection dragging */
        event.preventDefault ? event.preventDefault() : event.returnValue = false
      }

      /* while dragging */
      drag = function(event) {
        event = event || window.event

        if (element.startEvent) {
          /* calculate move position */
          var x, y
            , rotation  = element.startPosition.rotation
            , width     = element.startPosition.width
            , height    = element.startPosition.height
            , delta     = {
                x:    event.pageX - element.startEvent.pageX,
                y:    event.pageY - element.startEvent.pageY,
                zoom: element.startPosition.zoom
              }

          /* caculate new position [with rotation correction] */
          x = element.startPosition.x + (delta.x * Math.cos(rotation) + delta.y * Math.sin(rotation))  / element.startPosition.zoom
          y = element.startPosition.y + (delta.y * Math.cos(rotation) + delta.x * Math.sin(-rotation)) / element.startPosition.zoom

          /* move the element to its new position, if possible by constraint */
          if (typeof constraint === 'function') {
            var coord = constraint(x, y)

            if (typeof coord === 'object') {
              if (typeof coord.x != 'boolean' || coord.x)
                element.x(typeof coord.x === 'number' ? coord.x : x)
              if (typeof coord.y != 'boolean' || coord.y)
                element.y(typeof coord.y === 'number' ? coord.y : y)

            } else if (typeof coord === 'boolean' && coord) {
              element.move(x, y)
            }

          } else if (typeof constraint === 'object') {
            /* keep element within constrained box */
            if (constraint.minX != null && x < constraint.minX)
              x = constraint.minX
            else if (constraint.maxX != null && x > constraint.maxX - width)
              x = constraint.maxX - width

            if (constraint.minY != null && y < constraint.minY)
              y = constraint.minY
            else if (constraint.maxY != null && y > constraint.maxY - height)
              y = constraint.maxY - height

            //recalculation for flip x
            if(constraint.flipX!= null && constraint.flipX==-1){
               //tinh theo he truc duong, nen can nghich dao element x va width
                x = (-1)*element.startPosition.x + (delta.x * Math.cos(rotation) + delta.y * Math.sin(rotation))  / element.startPosition.zoom;
                if (constraint.minX != null && x < constraint.minX - width)
                  x = constraint.minX - width;
                else if (constraint.maxX != null && x > constraint.maxX)
                  x = constraint.maxX
            }

            //recalculation for flip x
            if(constraint.flipY!= null && constraint.flipY==-1){
               //tinh theo he truc duong, nen can nghich dao element x va width
                y = (-1)*element.startPosition.y + (delta.y * Math.cos(rotation) + delta.x * Math.sin(-rotation)) / element.startPosition.zoom
                if (constraint.minY != null && y < constraint.minY - height)
                  y = constraint.minY - height
                else if (constraint.maxY != null && y > constraint.maxY)
                  y = constraint.maxY
            }

            element.move(x, y)
          }

          /* invoke any callbacks */
          if (element.dragmove)
            element.dragmove(delta, event)
        }
      }

      /* when dragging ends */
      end = function(event) {
        event = event || window.event
        if( element.startEvent == undefined ) {
          element.startEvent = {};
        }
        if( element.startPosition == undefined ) {
          element.startPosition= {};
        }
        /* calculate move position */
        var delta = {
          x:    event.pageX - element.startEvent.pageX
        , y:    event.pageY - element.startEvent.pageY
        , zoom: element.startPosition.zoom
        }

        /* reset store */
        element.startEvent    = null
        element.startPosition = null

        /* remove while and end events to window */
        SVG.off(window, 'mousemove', drag)
        SVG.off(window, 'mouseup',   end)

        /* invoke any callbacks */
        if (element.dragend)
          element.dragend(delta, event)
      }

      /* bind mousedown event */
      element.on('mousedown', start)

      /* disable draggable */
      element.fixed = function() {
        element.off('mousedown', start)

        SVG.off(window, 'mousemove', drag)
        SVG.off(window, 'mouseup',   end)

        start = drag = end = null

        return element
      }

      return this
    }

  })

}).call(this);