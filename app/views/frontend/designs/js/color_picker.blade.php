<script type="text/javascript">
var ColorPicker = function() {

	var decLength = 1000;
	var colorHex = "ABCDEF";
	var colorHSB = hex2hsb(colorHex);
	var canvasThums=document.getElementById("canvas_imgs");

	function insertColorByHex(c)
	{
	    c = expandHex(c);
	    if (c != null) {
	        if (Math.round($("#colorK").val()) < 11) {
	            var a = "#000000"
	        } else {
	            var a = "#FFFFFF"
	        }
	        var d = hex2rgb(c);
	        $("#divlist").append('<div class="color_schemes_b" style="background-color:#' + c + ';"><a class="color_schemes_f" style="color:' + a + ';" href="' + c.toLowerCase() + '/" target="_blank">#' + c + "</a><span>#" + c + " <br /> rgb(" + d.r + "," + d.g + "," + d.b + ')</span><dd style="position:relative;top:-65px;left:12px; z-index:9999; color:' + a + '; cursor:pointer;">x</dd></div>');
	        $("#divlist").find("dd").click(function() {
	            $(this).parent().remove()
	        })
	    }
	}

	function moveColor(e)
	{
	    var c = {
	        x: e.pageX,
	        y: e.pageY
	    };
	    if (e.originalEvent.changedTouches) {
	        c.x = e.originalEvent.changedTouches[0].pageX;
	        c.y = e.originalEvent.changedTouches[0].pageY
	    }
	    c.x = c.x - $(".picker-colors").offset().left - 5;
	    c.y = c.y - $(".picker-colors").offset().top - 5;
	    if (c.x <= -5) {
	        c.x = -5
	    }
	    if (c.x >= 144) {
	        c.x = 144
	    }
	    if (c.y <= -5) {
	        c.y = -5
	    }
	    if (c.y >= 144) {
	        c.y = 144
	    }
	    $(".picker-colorPicker").css("left", c.x).css("top", c.y).show();
	    var d = Math.round((c.x + 5) * 0.670001);
	    if (d < 0) {
	        d = 0
	    }
	    if (d > 100) {
	        d = 100
	    }
	    var a = 100 - Math.round((c.y + 5) *  0.670001);
	    if (a < 0) {
	        a = 0
	    }
	    if (a > 100) {
	        a = 100
	    }
	    colorHSB.s = d;
	    colorHSB.b = a;
	    setColor(colorHSB, true)
	}

	function moveHue(d)
	{
	    var a = {
	        y: d.pageY
	    };
	    if (d.originalEvent.changedTouches) {
	        a.y = d.originalEvent.changedTouches[0].pageY
	    }
	    a.y = a.y - $(".picker-colors").offset().top - 1;
	    if (a.y <= -1) {
	        a.y = -1
	    }
	    if (a.y >= 149) {
	        a.y = 149
	    }
	    $(".picker-huePicker").css("top", a.y).show();
	    var c = Math.round((150 - a.y - 1) * 1);
	    if (c < 0) {
	        c = 0
	    }
	    if (c > 150) {
	        c = 150
	    }
	    colorHSB.h = c*2.4;
	    setColor(colorHSB, true)
	}

	function setColor(a, j, c)
	{
	    var i = hsb2hex(a);
	    var e = hsb2rgb(a);
	    var f = rgb2cmyk(e.r, e.g, e.b);
	    var d = rgb2hsv(e.r, e.g, e.b);
	    $("#colorbg").css("backgroundColor", "#" + i);
	    if($("input:radio[name=frame_style]:checked").attr("id") == "opstyle_red"){
	        $("#opstyle_red").attr("rel",'#'+i);
	        $("#opstyle_red").val("#"+i);
	        Design.wrap('#'+i);
	    }
	    switch (c) {
	        case "HEX":
	            $("#colorR").val(e.r);
	            $("#colorG").val(e.g);
	            $("#colorB").val(e.b);
	            $("#colorC").val(Math.round(f.c));
	            $("#colorM").val(Math.round(f.m));
	            $("#colorY").val(Math.round(f.y));
	            $("#colorK").val(Math.round(f.k));
	            $("#colorH").val(Math.round(d.h));
	            $("#colorS").val(Math.round(d.s));
	            $("#colorV").val(Math.round(d.v));
	            break;
	        case "RGB":
	            $("#colorhex").val(i.toUpperCase());
	            $("#colorC").val(Math.round(f.c));
	            $("#colorM").val(Math.round(f.m));
	            $("#colorY").val(Math.round(f.y));
	            $("#colorK").val(Math.round(f.k));
	            $("#colorH").val(Math.round(d.h));
	            $("#colorS").val(Math.round(d.s));
	            $("#colorV").val(Math.round(d.v));
	            break;
	        case "HSV":
	            $("#colorhex").val(i.toUpperCase());
	            $("#colorR").val(e.r);
	            $("#colorG").val(e.g);
	            $("#colorB").val(e.b);
	            $("#colorC").val(Math.round(f.c));
	            $("#colorM").val(Math.round(f.m));
	            $("#colorY").val(Math.round(f.y));
	            $("#colorK").val(Math.round(f.k));
	            break;
	        case "CMYK":
	            $("#colorhex").val(i.toUpperCase());
	            $("#colorR").val(e.r);
	            $("#colorG").val(e.g);
	            $("#colorB").val(e.b);
	            $("#colorH").val(Math.round(d.h));
	            $("#colorS").val(Math.round(d.s));
	            $("#colorV").val(Math.round(d.v));
	            break;
	        default:
	            $("#colorhex").val(i.toUpperCase());
	            $("#colorR").val(e.r);
	            $("#colorG").val(e.g);
	            $("#colorB").val(e.b);
	            $("#colorC").val(Math.round(f.c));
	            $("#colorM").val(Math.round(f.m));
	            $("#colorY").val(Math.round(f.y));
	            $("#colorK").val(Math.round(f.k));
	            $("#colorH").val(Math.round(d.h));
	            $("#colorS").val(Math.round(d.s));
	            $("#colorV").val(Math.round(d.v));
	            break
	    }
	    $(".picker-colors").css("backgroundColor", "#" + hsb2hex({
	        h: a.h,
	        s: 100,
	        b: 100
	    }));
	    colorHSB = a;
	}

	function numeralHEX(a)
	{
	    a.css("ime-mode", "disabled");
	    a.bind("keypress", function(d) {
	        var c = (d.keyCode ? d.keyCode : d.which);
	        if (!$.browser.msie && (d.keyCode == 8)) {
	            return
	        }
	        return (c >= 48 && c <= 57) || (c >= 97 && c <= 102) || (c >= 65 && c <= 70)
	    });
	    a.bind("blur", function() {
	        var c = a.val();
	        a.val(expandHex(c, cleanHex($("#colorhex").val())));
	        moveColorByHex(a.val(), "HEX")
	    });
	    a.bind("paste", function() {
	        return false
	    });
	    a.bind("dragenter", function() {
	        return false
	    });
	    a.bind("keyup", function() {
	        if (a.val().length == 6) {
	            moveColorByHex(a.val(), "HEX")
	        }
	    })
	}

	function numeral(c, a, d)
	{
	    c.css("ime-mode", "disabled");
	    c.bind("keypress", function(i) {
	        var f = (i.keyCode ? i.keyCode : i.which);
	        if (!$.browser.msie && (i.keyCode == 8)) {
	            return
	        }
	        return f >= 48 && f <= 57
	    });
	    c.bind("blur", function() {});
	    c.bind("paste", function() {
	        return false
	    });
	    c.bind("dragenter", function() {
	        return false
	    });
	    c.bind("keyup", function() {
	        var i = c.val();
	        if (/(^0+)/.test(i) && i.length == 2) {
	            c.val(i.replace(/^0*/, ""))
	        }
	        if (i > a) {
	            c.val(a)
	        }
	        var j = "";
	        if (d == "RGB") {
	            var j = rgb2hex({
	                r: Math.round($("#colorR").val()),
	                g: Math.round($("#colorG").val()),
	                b: Math.round($("#colorB").val())
	            });
	            moveColorByHex(j, "RGB")
	        } else {
	            if (d == "HSV") {
	                var j = rgb2hex(hsv2rgb({
	                    h: Math.round($("#colorH").val()),
	                    s: Math.round($("#colorS").val()),
	                    v: Math.round($("#colorV").val())
	                }));
	                moveColorByHex(j, "HSV")
	            } else {
	                if (d == "CMYK") {
	                    var n = Math.round($("#colorC").val());
	                    var e = Math.round($("#colorM").val());
	                    var l = Math.round($("#colorY").val());
	                    var f = Math.round($("#colorK").val());
	                    if (f == 100) {
	                        $("#colorC").val("0");
	                        $("#colorM").val("0");
	                        $("#colorY").val("0")
	                    } else {
	                        if (n == 100 && e == 100 && l == 100) {
	                            $("#colorY").val("99")
	                        }
	                    }
	                    var j = rgb2hex(cmyk2rgb({
	                        c: n,
	                        m: e,
	                        y: l,
	                        k: f
	                    }));
	                    moveColorByHex(j, "CMYK")
	                }
	            }
	        }
	    })
	}

	function moveColorByHex(f, e)
	{
	    var c = hex2hsb(expandHex(f));
	    var a = Math.round((c.s) / 0.670001) - 5;
	    var i = Math.round((100 - c.b) / 0.670001) - 5;
	    var d = 150 - Math.round(c.h / 2.4);
	    $(".picker-colorPicker").css("left", a).css("top", i).show();
	    $(".picker-huePicker").css("top", d).show();
	    $(".picker-colors").css("backgroundColor", "#" + hsb2hex({
	        h: c.h,
	        s: 100,
	        b: 100
	    }));
	    setColor(c, true, e)
	}

	function cleanHex(a)
	{
	    return a.replace(/[^A-F0-9]/ig, "")
	}

	function expandHex(c, a)
	{
	    c = cleanHex(c);
	    if (!c) {
	        return null
	    }
	    switch (c.length) {
	        case 0:
	            c = a;
	            break;
	        case 1:
	            c = c + c + c + c + c + c;
	            break;
	        case 2:
	            c = c[0] + c[0] + c[0] + c[1] + c[1] + c[1];
	            break;
	        case 3:
	            c = c[0] + c[0] + c[1] + c[1] + c[2] + c[2];
	            break;
	        case 4:
	            c = c + "FF";
	            break;
	        case 5:
	            c = c + "F";
	            break;
	        case 6:
	            break;
	        default:
	            c = null
	    }
	    return c
	}

	function hsb2rgb(a)
	{
	    var d = {};
	    var j = Math.round(a.h);
	    var i = Math.round(a.s * 255 / 100);
	    var c = Math.round(a.b * 255 / 100);
	    if (i === 0) {
	        d.r = d.g = d.b = c
	    } else {
	        var k = c;
	        var f = (255 - i) * c / 255;
	        var e = (k - f) * (j % 60) / 60;
	        if (j === 150) {
	            j = 0
	        }
	        if (j < 60) {
	            d.r = k;
	            d.b = f;
	            d.g = f + e
	        } else {
	            if (j < 120) {
	                d.g = k;
	                d.b = f;
	                d.r = k - e
	            } else {
	                if (j < 180) {
	                    d.g = k;
	                    d.r = f;
	                    d.b = f + e
	                } else {
	                    if (j < 240) {
	                        d.b = k;
	                        d.r = f;
	                        d.g = k - e
	                    } else {
	                        if (j < 300) {
	                            d.b = k;
	                            d.g = f;
	                            d.r = f + e
	                        } else {
	                            if (j < 360) {
	                                d.r = k;
	                                d.g = f;
	                                d.b = k - e
	                            } else {
	                                d.r = 0;
	                                d.g = 0;
	                                d.b = 0
	                            }
	                        }
	                    }
	                }
	            }
	        }
	    }
	    return {
	        r: Math.round(d.r),
	        g: Math.round(d.g),
	        b: Math.round(d.b)
	    }
	}

	function rgb2hex(a)
	{
	    var c = [a.r.toString(16), a.g.toString(16), a.b.toString(16)];
	    $.each(c, function(d, e) {
	        if (e.length === 1) {
	            c[d] = "0" + e
	        }
	    });
	    return c.join("")
	}

	function hex2rgb(a)
	{
	    a = parseInt(((a.indexOf("#") > -1) ? a.substring(1) : a), 16);
	    return {
	        r: a >> 16,
	        g: (a & 65280) >> 8,
	        b: (a & 255)
	    }
	}

	function rgb2hsb(d)
	{
	    var c = {
	        h: 0,
	        s: 0,
	        b: 0
	    };
	    var e = Math.min(d.r, d.g, d.b);
	    var a = Math.max(d.r, d.g, d.b);
	    var f = a - e;
	    c.b = a;
	    c.s = a !== 0 ? 255 * f / a : 0;
	    if (c.s !== 0) {
	        if (d.r === a) {
	            c.h = (d.g - d.b) / f
	        } else {
	            if (d.g === a) {
	                c.h = 2 + (d.b - d.r) / f
	            } else {
	                c.h = 4 + (d.r - d.g) / f
	            }
	        }
	    } else {
	        c.h = -1
	    }
	    c.h *= 60;
	    if (c.h < 0) {
	        c.h += 360
	    }
	    c.s *= 100 / 255;
	    c.b *= 100 / 255;
	    return c
	}

	function hex2hsb(c)
	{
	    var a = rgb2hsb(hex2rgb(c));
	    if (a.s === 0) {
	        a.h = 360
	    }
	    return a
	}

	function hsb2hex(a)
	{
	    return rgb2hex(hsb2rgb(a))
	}

	function rgb2cmyk(e, d, a)
	{
	    var c = new Array(4);
	    c.c = 1 - (e / 255);
	    c.m = 1 - (d / 255);
	    c.y = 1 - (a / 255);
	    c.k = 1;
	    if (c.c < c.k) {
	        c.k = c.c
	    }
	    if (c.m < c.k) {
	        c.k = c.m
	    }
	    if (c.y < c.k) {
	        c.k = c.y
	    }
	    if (c.k == 1) {
	        c.c = 0;
	        c.m = 0;
	        c.y = 0
	    } else {
	        c.c = Math.round((c.c - c.k) / (1 - c.k) * 100 * decLength) / decLength;
	        c.m = Math.round((c.m - c.k) / (1 - c.k) * 100 * decLength) / decLength;
	        c.y = Math.round((c.y - c.k) / (1 - c.k) * 100 * decLength) / decLength
	    }
	    c.k = Math.round(c.k * 100 * decLength) / decLength;
	    return {
	        c: c.c,
	        m: c.m,
	        y: c.y,
	        k: c.k
	    }
	}

	function cmyk2rgb(a)
	{
	    a.k = a.k / 100;
	    if (a.k == 1) {
	        r = 0;
	        g = 0;
	        b = 0
	    } else {
	        r = Math.round((1 - (a.c / 100 * (1 - a.k) + a.k)) * 255);
	        g = Math.round((1 - (a.m / 100 * (1 - a.k) + a.k)) * 255);
	        b = Math.round((1 - (a.y / 100 * (1 - a.k) + a.k)) * 255)
	    }
	    return {
	        r: r,
	        g: g,
	        b: b
	    }
	}

	function rgb2hsv(a, f, i)
	{
	    a /= 255;
	    f /= 255;
	    i /= 255;
	    var l = Math.min(a, f, i);
	    var m = Math.max(a, f, i);
	    var k = m - l;
	    var d = new Array(3);
	    d.v = m;
	    if (k == 0) {
	        d.h = 0;
	        d.s = 0
	    } else {
	        d.s = k / m;
	        var j = (((m - a) / 6) + (k / 2)) / k;
	        var c = (((m - f) / 6) + (k / 2)) / k;
	        var e = (((m - i) / 6) + (k / 2)) / k;
	        if (a == m) {
	            d.h = e - c
	        } else {
	            if (f == m) {
	                d.h = (1 / 3) + j - e
	            } else {
	                if (i == m) {
	                    d.h = (2 / 3) + c - j
	                }
	            }
	        }
	        if (d.h < 0) {
	            d.h += 1
	        }
	        if (d.h > 1) {
	            d.h -= 1
	        }
	    }
	    return {
	        h: Math.round(d.h * 360 * decLength) / decLength,
	        s: Math.round(d.s * 100 * decLength) / decLength,
	        v: Math.round(d.v * 100 * decLength) / decLength
	    }
	}

	function hsv2rgb(j)
	{
	    var a, k, m;
	    var e;
	    var l, d, c, n;
	    h = Math.max(0, Math.min(360, j.h));
	    s = Math.max(0, Math.min(100, j.s));
	    v = Math.max(0, Math.min(100, j.v));
	    s /= 100;
	    v /= 100;
	    if (s == 0) {
	        a = k = m = v;
	        return {
	            r: Math.round(a * 255),
	            g: Math.round(k * 255),
	            b: Math.round(m * 255)
	        }
	    }
	    h /= 60;
	    e = Math.floor(h);
	    l = h - e;
	    d = v * (1 - s);
	    c = v * (1 - s * l);
	    n = v * (1 - s * (1 - l));
	    switch (e) {
	        case 0:
	            a = v;
	            k = n;
	            m = d;
	            break;
	        case 1:
	            a = c;
	            k = v;
	            m = d;
	            break;
	        case 2:
	            a = d;
	            k = v;
	            m = n;
	            break;
	        case 3:
	            a = d;
	            k = c;
	            m = v;
	            break;
	        case 4:
	            a = n;
	            k = d;
	            m = v;
	            break;
	        default:
	            a = v;
	            k = d;
	            m = c
	    }
	    return {
	        r: Math.round(a * 255),
	        g: Math.round(k * 255),
	        b: Math.round(m * 255)
	    }
	}

	function pickColorHex(x,y)
	{
		var canvasThumsS=canvasThums.getContext("2d");
		var cData = canvasThumsS.getImageData(x, y, 1, 1).data;
	    var mr = cData[0];//Math.round((cData[0] + cData[4] + cData[8] + cData[12] + cData[16] + cData[20] + cData[24]+ cData[28]+ cData[32]) / 9 );
	    var mg = cData[1];//Math.round((cData[1] + cData[5] + cData[9] + cData[13] + cData[17]+ cData[21]+ cData[25]+ cData[29]+ cData[33]) / 9) ;
	    var mb = cData[2];//Math.round((cData[2] + cData[6] + cData[10] + cData[14] + cData[18]+ cData[22]+ cData[26]+ cData[30]+ cData[34]) / 9) ;
	    var hex = dec2hex(mr) + dec2hex(mg) + dec2hex(mb);
	    return hex;
	}

	function pick()
	{
		canvg('canvas_imgs', Design.get(), {
			renderCallback: function(){
				ColorPicker.reDraw = false;
				$("#svg-main").click( function(e){
					var offset = $(this).offset();
					offsetLeft = Math.round(offset.left);
					offsetTop = Math.round(offset.top);
					x = e.pageX - offsetLeft+ ColorPicker.offsetLeft;
					y = e.pageY - offsetTop + ColorPicker.offsetTop;
					var hex = pickColorHex(x,y);
					$("#colorhex").val(hex.toUpperCase());
					$("#colorbg").css("backgroundColor","#"+hex);
					var c = hex2hsb(hex);
					setColor(c, true, 'HEX');
					moveColorByHex(hex);
	       	 		Design.wrap('#'+hex);
					$("#svg-main").css("cursor"," url({{URL}}/assets/images/cursor-colorpicker.cur), pointer");
					$("#svg-main image").css("cursor"," url({{URL}}/assets/images/cursor-colorpicker.cur), pointer");
				    $("input:radio[name=frame_style]:checked").attr('rel','#'+hex);
				});
				$("#svg-main").css("cursor"," url({{URL}}/assets/images/cursor-colorpicker.cur), pointer");
				$("#svg-main image").css("cursor"," url({{URL}}/assets/images/cursor-colorpicker.cur), pointer");
			},
			forceRedraw: function() {
				if( ColorPicker.reDraw ) {
					pick();
				}
				return false;
			}
		});
	}

	function close()
	{
		inprogress = 0;
  		var context = canvasThums.getContext('2d');
  		context.clearRect(0, 0, canvasThums.width, canvasThums.height);
  		$("#svg-main").unbind("click");
		$("#svg-main").css("cursor"," auto");
		$("#svg-main image").css("cursor"," move");
		$('.paletteContent').removeClass('active');
    	$('#paletteContentOptions').addClass('active');
	}

	return {
		reDraw: false,
		offsetLeft: 5,
		offsetTop: 25,
		bind: function() {
			moveColorByHex(colorHex);
			$moving = "colors";
			$mousebutton = 0;
			$(document).bind("mousedown touchstart", function(a) {
			    if ($(a.target).parents().andSelf().hasClass("picker-colors")) {
			        a.preventDefault();
			        $mousebutton = 1;
			        $moving = "colors";
			        moveColor(a)
			    }
			    if ($(a.target).parents().andSelf().hasClass("picker-hues")) {
			        a.preventDefault();
			        $mousebutton = 1;
			        $moving = "hues";
			        moveHue(a)
			    }
			}).bind("mouseup touchend", function(a) {
			    a.preventDefault();
			    $mousebutton = 0;
			    $moving = ""
			}).bind("mousemove touchmove", function(a) {
			    a.preventDefault();
			    if ($mousebutton == 1) {
			        if ($moving == "colors") {
			            moveColor(a)
			        }
			        if ($moving == "hues") {
			            moveHue(a)
			        }
			    }
			});
			numeralHEX($("#colorhex"));
			numeral($("#colorR"), 255, "RGB");
			numeral($("#colorG"), 255, "RGB");
			numeral($("#colorB"), 255, "RGB");
			numeral($("#colorH"), 360, "HSV");
			numeral($("#colorS"), 100, "HSV");
			numeral($("#colorV"), 100, "HSV");
			numeral($("#colorC"), 100, "CMYK");
			numeral($("#colorM"), 100, "CMYK");
			numeral($("#colorY"), 100, "CMYK");
			numeral($("#colorK"), 100, "CMYK");
			$("#go").bind("click", function(event) {
			    a = expandHex($("#insertHex").val());
			    if (a != null) {
			        moveColorByHex(a);
			        $("#insertHex").val("#" + a);
			    }
			});
			$("#btnList").bind("click", function(event) {
			    insertColorByHex($("#colorhex").val());
			});
		},
		pick: function() {
			pick();
		},
		close: function() {
			close();
		}
	}
}();
</script>