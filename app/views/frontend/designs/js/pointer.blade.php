<script type="text/javascript">
var Pointer = function(){
	var __distance;
	var __pointA;
	var __pointB;
	var __pointC;

	function __set(d, a, b, c)
	{
		__distance = d;
		__pointA = a;
		__pointB = b;
		__pointC = c;
	}

	function linearEquations(mode)
	{
		var vectorAB = {};
		var normalvector = {};
		vectorAB.x = __pointB.x - __pointA.x;
		vectorAB.y = __pointB.y - __pointA.y;
		normalvector.x = -1*vectorAB.y;
		normalvector.y = vectorAB.x;
		var line = {};
		if( mode == 'perpendicular' ) {
			line.a = vectorAB.x;
			line.b = vectorAB.y;
			line.c = vectorAB.x*__pointA.x + vectorAB.y*__pointA.y;
		} else {
			line.a = normalvector.x;
			line.b = normalvector.y;
			line.c = normalvector.x*__pointA.x + normalvector.y*__pointA.y;
		}
		return line;
	}

	function linearParallel(line)
	{
		var lineParallelA = {};
		var lineParallelB = {};
		var arrLine = [];

		lineParallelA.a = line.a;
		lineParallelA.b = line.b;
		lineParallelA.c = (__distance*Math.sqrt(line.a*line.a + line.b*line.b)) + line.c;
		lineParallelB.a = line.a;
		lineParallelB.b = line.b;
		lineParallelB.c = -1*(__distance*Math.sqrt(line.a*line.a + line.b*line.b)) + line.c;
		arrLine[0] = lineParallelA;
		arrLine[1] = lineParallelB;

		return arrLine;
	}

	function coorNode(A, B)
	{
		var m = {};
		var tmp = A.a*B.b - A.b*B.a;
		if(tmp != 0) {
			m.y = (A.a*B.c - A.c*B.a)/tmp;
		} else {
			m.y = 0;
		}
		if(A.a != 0) {
			m.x = (A.c - A.b*m.y)/A.a;
		} else if(B.a!=0) {
			m.x = (B.c - B.b*m.y)/B.a;
		} else {
			m.x = 0;
		}
		return m;
	}

	function sameSide(line, point)
	{
		var A = line.a*__pointC.x + line.b*__pointC.y - line.c;
		var B = line.a*point.x + line.b*point.y - line.c;
		if( (A>0 && B>0) || (A<0 && B<0)) {
			return true;
		} else {
			return false;
		}
	}
	function angle(P1,P2,P3)
	{
		P12angle = slant(P1,P2);
		// P12deg = Math.floor(180*P12angle/Math.PI);
		P12deg = 180*P12angle/Math.PI;
		P23angle = slant(P3,P2);
		// P23deg = Math.floor(180*P23angle/Math.PI);
		P23deg = 180*P23angle/Math.PI;
		var a = P23deg - P12deg;
		if (a<0) a=360+a;
		return a;
	}
	function slant(P1,P2)
	{
		dx=(P1.x-P2.x)
		dy=(P1.y-P2.y)
		if ((dx*dx+dy*dy)==0) alpha=0
		else	alpha=Math.acos(dx/Math.sqrt(dx*dx+dy*dy))
		if (dy<0) alpha=2*Math.PI-alpha
		return alpha
	}

	return {
		find : function(d, a, b, c, reverse) {
			__set(d, a, b, c);
			var perpendicularLine = linearEquations('perpendicular');
			var line = linearEquations();
			var parallelLine = linearParallel(line);
			var nodeA = coorNode(perpendicularLine, parallelLine[0]);
			var nodeB = coorNode(perpendicularLine, parallelLine[1]);
			if(!sameSide(line, nodeA)) {
				if( reverse == 'reverse' ) {
					return nodeB;
				}
				return nodeA;
			} else if(!sameSide(line, nodeB)) {
				if( reverse == 'reverse' ) {
					return nodeA;
				}
				return nodeB;
			}
			return false;
		},
		linearEquations: function(a, b, mode) {
			__set(undefined, a, b);
			return linearEquations(mode);
		},
		linearParallel: function(d, line) {
			__set(d);
			return linearParallel(line);
		},
		coorNode: function(line, point) {
			return coorNode(line, point);
		},
		sameSide: function(line, pointA, pointB) {
			__set(undefined, undefined, undefined, pointA);
			return sameSide(line, pointB);
		},
		angle: function(pointA, pointB, pointC) {
			return angle(pointA, pointB, pointC);
		}
	};
}();
/*
* Example:
*	var pointA = {x: 8,  y: 9};
*	var pointB = {x: 5,  y: 7};
*	var pointC = {x: 15, y: 8};
*	var distance = 10;
*	var m = Pointer.find(distance, pointA, pointB, pointC);
*
*/
</script>