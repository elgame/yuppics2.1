(function ($) {
    $.fn.extend({
        pschecker: function (options) {
            var settings = $.extend({ minlength: 8, maxlength: 16, onPasswordValidate: null, onPasswordMatch: null }, options);
            return this.each(function () {
                var wrapper = $('.password-container');
                var password = $('.strong-password:eq(0)', wrapper);
                var cPassword = $('.strong-password:eq(1)', wrapper);

                cPassword.removeClass('no-match');
                password.keyup(validatePassword).blur(validatePassword).focus(validatePassword);
                cPassword.keyup(validatePassword).blur(validatePassword).focus(validatePassword);

                function validatePassword() {
                    var pstr = password.val().toString();
                    var meter = $('.meter');
                    meter.html("");
                    //fires password validate event if password meets the min length requirement
                    if (settings.onPasswordValidate != null)
                        settings.onPasswordValidate(pstr.length >= settings.minlength);

                    if (pstr.length < settings.maxlength)
                        meter.removeClass('strong').removeClass('medium').removeClass('week');
                    if (pstr.length > 0) {
                        var rx = new RegExp(/^(?=(.*[a-z]){1,})(?=(.*[\d]){1,})(?=(.*[\W]){1,})(?!.*\s).{7,30}$/);
                        if (rx.test(pstr)) {
                            meter.addClass('strong');
                            meter.html("Fuerte");
                        }
                        else {
                            var alpha = containsAlpha(pstr);
                            var number = containsNumeric(pstr);
                            var upper = containsUpperCase(pstr);
                            var special = containsSpecialCharacter(pstr);
                            var result = alpha + number + upper + special;

                            if (result > 2) {
                                meter.addClass('medium');
                                meter.html("Media");
                            }
                            else {
                                meter.addClass('week');
                                meter.html("Debil");
                            }
                        }
                        if (cPassword.val().toString().length > 0) {
                            if (pstr == cPassword.val().toString()) {
                                cPassword.removeClass('no-match');
                                if (settings.onPasswordMatch != null)
                                    settings.onPasswordMatch(true);
                            }
                            else {
                                cPassword.addClass('no-match');
                                if (settings.onPasswordMatch != null)
                                    settings.onPasswordMatch(false);
                            }
                        }
                        else {
                            cPassword.addClass('no-match');
                            if (settings.onPasswordMatch != null)
                                settings.onPasswordMatch(false);
                        }
                    }
                }

                function containsAlpha(str) {
                    var rx = new RegExp(/[a-z]/);
                    if (rx.test(str)) return 1;
                    return 0;
                }

                function containsNumeric(str) {
                    var rx = new RegExp(/[0-9]/);
                    if (rx.test(str)) return 1;
                    return 0;
                }

                function containsUpperCase(str) {
                    var rx = new RegExp(/[A-Z]/);
                    if (rx.test(str)) return 1;
                    return 0;
                }
                function containsSpecialCharacter(str) {

                    var rx = new RegExp(/[\W]/);
                    if (rx.test(str)) return 1;
                    return 0;
                }


            });
        }
    });
})(jQuery);

$(function(){
    $('.password-container').pschecker();
    // { onPasswordValidate: validatePassword, onPasswordMatch: matchPassword }
});


//this function will handle onPasswordValidate callback, which mererly checks the password against minimum length
// function validatePassword(isValid) {
//     if (!isValid)
//         errorBox.css('visibility', 'visible');
//     else
//         errorBox.css('visibility', 'hidden');
// }
// //this function will be called when both passwords match
// function matchPassword(isMatched) {
//     if (isMatched) {
//         submitbutton.addClass('unlocked').removeClass('locked');
//         submitbutton.removeAttr("disabled", "disabled");
//     }
//     else {
//         submitbutton.attr("disabled", "disabled");
//         submitbutton.addClass('locked').removeClass('unlocked');
//     }
// }