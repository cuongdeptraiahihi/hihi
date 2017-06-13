(function ($) {
    $.fn.county = function (options) {
        var settings = $.extend({ endDateTime: new Date(), animation: 'fade', reflection: true, reflectionOpacity: 0.2, speed: 500, theme: 'black' }, options);
		
		days_of_month=[31,28,31,30,31,30,31,31,30,31,30,31];
		var d = new Date();
		var y = d.getFullYear();
		if(y%4==0) {
			days_of_month[1]=29;
		}
		var n = d.getMonth() + 1;
		var all_days=days_of_month[n-1];

        return this.each(function () {
            var timeoutInterval = null;
            var container = $(this);
            container.addClass('county ' + settings.theme);
            container.append('<span class="county-days-wrapper first"><span class="county-days">000</span></span><span class="county-hours-wrapper separator-left border-separator-right"><span class="county-hours">00</span></span><span class="county-minutes-wrapper separator-left separator-left"><span class="county-minutes">00</span></span><span class="county-seconds-wrapper separator-left last"><span class="county-seconds">00</span></span><div class="clear"></div><span class="county-label-days">Ngày</span><span class="county-label-hours">Giờ</span><span class="county-label-minutes">Phút</span><span class="county-label-seconds">Giây</span>');
            if (container.attr('id') == undefined || container.attr('id') == null) {
                container.attr('id', Math.random());
            }

            var reflectionContainer = container.clone().css({ opacity: settings.reflectionOpacity }).attr('id', container.attr('id') + '-refl').addClass('county-reflection');
            if (settings.reflection)
                container.after(reflectionContainer);

            updateCounter();

            getCountDown();

            function getCountDown() {
                clearTimeout(timeoutInterval);
                timeoutInterval = setTimeout(function () {

                    updateCounter();

                }, 1000);
            }
            function updateCounter() {
                var countDown = getCurrentCountDown();
                var days = container.find('.county-days');
                var hours = container.find('.county-hours');
                var minutes = container.find('.county-minutes');
                var seconds = container.find('.county-seconds');
				
				var con1 = container.find('.county-label-days');
                var con2 = container.find('.county-label-hours');
                var con3 = container.find('.county-label-minutes');
                var con4 = container.find('.county-label-seconds');
				
				con1.html(countDown.con1);
				con2.html(countDown.con2);
				con3.html(countDown.con3);
				con4.html(countDown.con4);

                var dayVal = days.html();
                var hourVal = hours.html();
                var minuteVal = minutes.html();
                var secondVal = seconds.html();

                if (dayVal == countDown.days) {
                    days.html(countDown.days);
                }
                else {
                    if (settings.reflection)
                        aimateObject(days, reflectionContainer.find('.county-days'), dayVal, countDown.days, settings.animation);
                    else
                        aimateObject(days, null, dayVal, countDown.days, settings.animation);
                }

                if (hourVal == countDown.hours)
                    hours.html(countDown.hours);
                else {
                    if (settings.reflection)
                        aimateObject(hours, reflectionContainer.find('.county-hours'), hourVal, countDown.hours, settings.animation);
                    else
                        aimateObject(hours, null, hourVal, countDown.hours, settings.animation);
                }

                if (minuteVal == countDown.minutes)
                    minutes.html(countDown.minutes);
                else {
                    if (settings.reflection)
                        aimateObject(minutes, reflectionContainer.find('.county-minutes'), minuteVal, countDown.minutes, settings.animation);
                    else
                        aimateObject(minutes, null, minuteVal, countDown.minutes, settings.animation);
                }
                if (secondVal == countDown.seconds)
                    seconds.html(countDown.seconds);
                else {
                    if (settings.reflection)
                        aimateObject(seconds, reflectionContainer.find('.county-seconds'), secondVal, countDown.seconds, settings.animation);
                    else
                        aimateObject(seconds, null, secondVal, countDown.seconds, settings.animation);
                }

                getCountDown();
            }
            function aimateObject(element, reflectionElement, oldValue, newValue, type) {
                if (type == 'fade') {
                    element.fadeOut('fast').fadeIn('fast').html(newValue);
                    if (settings.reflection)
                        reflectionElement.fadeOut('fast').fadeIn('fast').html(newValue);
                }
                else if (type == 'scroll') {
                    var copy = element.clone();
                    var reflectionCopy = null;

                    if (settings.reflection)
                        reflectionCopy = reflectionElement.clone();

                    var marginTop = copy.outerHeight();

                    copy.css({ marginTop: -40 });
                    copy.html(newValue);
                    copy.prependTo(element.parent());

                    if (settings.reflection) {
                        reflectionCopy.css({ marginTop: -40 });
                        reflectionCopy.html(newValue);
                        reflectionCopy.prependTo(reflectionElement.parent());
                    }

                    element.animate({ marginTop: 40 }, settings.speed, function () { $(this).remove(); });
                    copy.animate({ marginTop: 0 }, settings.speed, function () { });

                    if (settings.reflection) {
                        reflectionElement.animate({ marginTop: 40 }, settings.speed, function () { $(this).remove(); });
                        reflectionCopy.animate({ marginTop: 0 }, settings.speed, function () { });
                    }

                }

            }
            function getCurrentCountDown() {

                //var endDateTime = new Date('2012/12/25 10:00:00');
                var currentDateTime = new Date();
				
				now_year = currentDateTime.getFullYear();
				now_month = currentDateTime.getMonth()>=12 ? 1:currentDateTime.getMonth()+1;
				now_day = currentDateTime.getDate();
				now_hour = currentDateTime.getHours();
				now_min = currentDateTime.getMinutes();
				now_sec = currentDateTime.getSeconds();
				
				var furDateTime = settings.endDateTime;
				
				fur_year = furDateTime.getFullYear();
				fur_month = furDateTime.getMonth()>=12 ? 1:furDateTime.getMonth()+1;
				fur_day = furDateTime.getDate();
				fur_hour = furDateTime.getHours();
				fur_min = furDateTime.getMinutes();
				fur_sec = furDateTime.getSeconds();
				
				year = fur_year - now_year;
				month = fur_month - now_month;
				day = all_days - now_day + fur_day;
				hour = 24 - now_hour + fur_hour;
				mins = 60 - now_min + fur_min;
				sec = 60 - now_sec + fur_sec;
				
				if(year>0) {
					//data = "Còn " + year + " năm, " + month + " tháng, " + day + " ngày, " + hour + " giờ";
					return { days: year, hours: Math.abs(month), minutes: day, seconds: hour, con1: "Năm", con2: "Tháng", con3: "Ngày", con4: "Giờ" };
				} else {
					if(month>1) {
						//data = "Còn " + month + " tháng, " + day + " ngày, " + hour + " giờ, " + mins + " phút";
						return { days: month, hours: day, minutes: hour, seconds: mins, con1: "Tháng", con2: "Ngày", con3: "Giờ", con4: "Phút" };
					} else {
						//data = "Còn " + day + " ngày, " + hour + " giờ, " + mins + " phút, " + sec + " giây";
						return { days: day, hours: hour, minutes: mins, seconds: sec, con1: "Ngày", con2: "Giờ", con3: "Phút", con4: "Giây" };
					}
				}

                /*var diff = parseFloat(settings.endDateTime - currentDateTime);

                var seconds = 0;
                var minutes = 0;
                var hours = 0;
                var total = parseFloat(((((diff / 1000.0) / 60.0) / 60.0) / 24.0));

                var days = parseInt(total);

                total -= days;

                total *= 24.0;

                hours = parseInt(total);

                total -= hours;

                total *= 60.0;

                minutes = parseInt(total);

                total -= minutes;

                total *= 60;

                seconds = parseInt(total);

                return { days: formatNumber(Math.max(0, days), true), hours: formatNumber(Math.max(0, hours), false), minutes: formatNumber(Math.max(0, minutes), false), seconds: formatNumber(Math.max(0, seconds), false) };*/

            }
            function formatNumber(number, isday) {
                var strNumber = number.toString();
                if (!isday) {
                    if (strNumber.length == 1)
                        return '0' + strNumber;
                    else
                        return strNumber;
                }
                else {
                    if (strNumber.length == 1)
                        return '00' + strNumber;
                    else if (strNumber == 2)
                        return '0' + strNumber;
                    else
                        return strNumber;
                }
            }
            function getHunderth(number) {
                var strNumber = '' + number;

                if (strNumber.length == 3)
                    return strNumber.substr(0, 1);
                else
                    return '0';
            }
            function getTenth(number) {

                var strNumber = '' + number;

                if (strNumber.length == 2)
                    return strNumber.substr(0, 1);
                else
                    return '0';
            }

            function getUnit(number) {
                var strNumber = '' + number;

                if (strNumber.length >= 1)
                    return strNumber.substr(0, 1);
                else
                    return '0';
            }
            return this;
        });
    }
})(jQuery);