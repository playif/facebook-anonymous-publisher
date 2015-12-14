(function($) {
  "use strict";  return $.fn.xxcountdown = function(o) {
    var obj;

    obj = this;
    this.opts = $.extend({
      dayText: ' 天 ',
      daysText: ' 天 ',
      hourText: ':',
      hoursText: ':',
      minuteText: ':',
      minutesText: ':',
      secondText: '',
      secondsText: '',
      finishText: '---',
      displayDays: true,
      displayHours: true,
      displayMinutes: true,
      displaySeconds: true,
      callback: function() {}
    }, o);
    this.init = function() {
      var count;

      if (this.data('seconds')) {
        count = this.data('seconds');
        count += this.current();
      }
      if (this.data('timestamp')) {
        count = this.data('timestamp');
      }
      if (count <= this.current()) {
        return this.finish();
      } else {
        return this.countdown(count);
      }
    };
    this.countdown = function(target) {
      var interval, _this;

      _this = this;
      return interval = setInterval(function() {
        if (_this.current() >= target) {
          clearInterval(interval);
          return _this.finish();
        } else {
          return _this.updateText(target - _this.current());
        }
      }, 100);
    };
    this.finish = function() {
      this.updateText(this.opts.finish);
      document.title = this.opts.finish;
      return this.opts.callback();
    };
    this.updateText = function(n) {
      if (typeof n === 'number') {
        n = this.formatTime(n);
      }
      this.text(n);
      return document.title = n;
    };
    this.formatTime = function(n) {
      var d, h, m, result, s;

      d = Math.floor(n / 86400);
      h = Math.floor((n - (d * 86400)) / 3600);
      m = Math.floor((n - (d * 86400) - (h * 3600)) / 60);
      s = Math.round(n - (d * 86400) - (h * 3600) - (m * 60));
      h = h < 10 ? '0' + h : h;
      m = m < 10 ? '0' + m : m;
      s = s < 10 ? '0' + s : s;
      result = '';
      if (this.opts.displayDays && d > 0) {
        result += d + (d <= 1 ? this.opts.dayText : this.opts.daysText);
      }
      if (this.opts.displayHours) {
        result += h + (h <= 1 ? this.opts.hourText : this.opts.hoursText);
      }
      if (this.opts.displayMinutes) {
        result += m + (m <= 1 ? this.opts.minuteText : this.opts.minutesText);
      }
      if (this.opts.displaySeconds) {
        result += s + (s <= 1 ? this.opts.secondText : this.opts.secondsText);
      }
      return result;
    };
    this.current = function() {
      return Math.floor(Date.now() / 1000);
    };
    return this.init();
  };
})(jQuery);
