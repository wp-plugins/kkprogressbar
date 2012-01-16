/**
 * jQuery Validation Plugin 1.9.0
 *
 * http://bassistance.de/jquery-plugins/jquery-plugin-validation/
 * http://docs.jquery.com/Plugins/Validation
 *
 * Copyright (c) 2006 - 2011 Jörn Zaefferer
 *
 * Dual licensed under the MIT and GPL licenses:
 *   http://www.opensource.org/licenses/mit-license.php
 *   http://www.gnu.org/licenses/gpl.html
 */

(function() {

	function stripHtml(value) {
		// remove html tags and space chars
		return value.replace(/<.[^<>]*?>/g, ' ').replace(/&nbsp;|&#160;/gi, ' ')
		// remove numbers and punctuation
		.replace(/[0-9.(),;:!?%#$'"_+=\/-]*/g,'');
	}
	jQuery.validator.addMethod("maxWords", function(value, element, params) {
	    return this.optional(element) || stripHtml(value).match(/\b\w+\b/g).length < params;
	}, jQuery.validator.format("Please enter {0} words or less."));

	jQuery.validator.addMethod("minWords", function(value, element, params) {
	    return this.optional(element) || stripHtml(value).match(/\b\w+\b/g).length >= params;
	}, jQuery.validator.format("Please enter at least {0} words."));

	jQuery.validator.addMethod("rangeWords", function(value, element, params) {
	    return this.optional(element) || stripHtml(value).match(/\b\w+\b/g).length >= params[0] && value.match(/bw+b/g).length < params[1];
	}, jQuery.validator.format("Please enter between {0} and {1} words."));

})();

jQuery.validator.addMethod("letterswithbasicpunc", function(value, element) {
	return this.optional(element) || /^[a-z-.,()'\"\s]+$/i.test(value);
}, "Letters or punctuation only please");

jQuery.validator.addMethod("alphanumeric", function(value, element) {
	return this.optional(element) || /^\w+$/i.test(value);
}, "Letters, numbers, spaces or underscores only please");

jQuery.validator.addMethod("lettersonly", function(value, element) {
	return this.optional(element) || /^[a-z]+$/i.test(value);
}, "Letters only please");

jQuery.validator.addMethod("nowhitespace", function(value, element) {
	return this.optional(element) || /^\S+$/i.test(value);
}, "No white space please");

jQuery.validator.addMethod("ziprange", function(value, element) {
	return this.optional(element) || /^90[2-5]\d\{2}-\d{4}$/.test(value);
}, "Your ZIP-code must be in the range 902xx-xxxx to 905-xx-xxxx");

jQuery.validator.addMethod("integer", function(value, element) {
	return this.optional(element) || /^-?\d+$/.test(value);
}, "A positive or negative non-decimal number please");

jQuery.validator.addMethod("selectWithout0", function(value) {
	return value != 0;
}, 'Please select an item!');

jQuery.validator.addMethod("onlyLetters", function(value) {
	return /^[a-zA-ZąćęłńóśżźĄĆĘŁŃÓŚŻŹ\-\x20]{1,}$/.test(value);
}, 'You can use only letters!');

jQuery.validator.addMethod("onlyDigits", function(value) {
	return /^[0-9\x20\x2d]*$/.test(value);
}, 'You can use only digits!');

jQuery.validator.addMethod("moreThenZero", function(value) {
	if(value > 0){
		return true;
	}else{
		return false;
	}
}, 'The value must be greater than zero.');

jQuery.validator.addMethod("mniejszaOd", function(value, element, relatedField) {
	var valueEnd = parseInt(jQuery(relatedField).val());
	var value = parseInt(value);
	
	if(value <= valueEnd){
		return true;
	}else{
		return false;
	}
}, 'The value must be less than or equal to the target value.');

jQuery.validator.addMethod("validatePostalCode", function(value) {
	if( $("#countryCode option:selected").val() != "pl") {
		return /^[A-Za-z0-9\s\-]{1,10}$/.test(value);
	} else {
		return /^[0-9]{2}-[0-9]{3}$/.test(value);
	}
}, 'Proszę wprowadzić poprawny kod pocztowy!');

jQuery.validator.addMethod("validatePolishPostalCode", function(value) {
	return /^[0-9]{2}-[0-9]{3}$/.test(value);
}, 'Proszę wprowadzić poprawny kod pocztowy!');

jQuery.validator.addMethod("validateUrl", function(value) {
	return /^(?:(?:ht|f)tps?\:\/\/)(?:[^\:;@#]+(?:(?:\:?[^\:;@#]+)|\:?)?@)?(?:(?:[a-z0-9\-_\xb1\xb3\xb6\xbc\xbf\xe4\xe6\xea\xf1\xf3\xf6\xfc\u0105\u0107\u0119\u0142\u0144\u015b\u017a\u017c]{1,64}\.){1,4}[a-z]{2,6}|(?:(?:[0-1]?[0-9]{1,2}|2[0-4][0-9]|25[0-5])\.){3}(?:[0-1]?[0-9]{1,2}|2[0-4][0-9]|25[0-5]))(?::[0-9]{2,5})?(?:(?:\/.*)|\/?)$/.test(value.toLowerCase());	
}, 'Entered the url is invalid!');

jQuery.validator.addMethod("validatePesel", function(value) {
	var weights = [1, 3, 7, 9, 1, 3, 7, 9, 1, 3];
	if (value == null || !checkControlSum(value, weights, 10, false))
	{
		return false;
	}
	var peselAnalyzer = new NetartPeselAnalyzer(value.toString(), new Date);
	if(!peselAnalyzer.isValid())
	{
		return false;
	}
	return true;
}, 'Niepoprawny numer PESEL!');

jQuery.validator.addMethod("validateNip", function(value) {
	if (value != '') {
		var getFieldPattern = function() {
			return /^\d{10}|\d{3}\-\d{3}\-\d{2}\-\d{2}|\d{3}\-\d{2}\-\d{2}\-\d{3}$/;
		}
		var regexp = new RegExp(getFieldPattern());
		var weights = [6,5,7,2,3,4,5,6,7];
		if (value == null || !checkControlSum(value, weights, 11, true) || !regexp.test(value) )
		{
			return false;
		}
	}
	return true;
}, 'Niepoprawny numer NIP!');

jQuery.validator.addMethod("validateRegon", function(value) {
	var controlSum = false;
	var mixedLength = value.length;
	if (mixedLength == 9)
	{
		var weights = [8, 9, 2, 3, 4, 5, 6, 7];
		controlSum = checkControlSum(value, weights, 11, true);
	}
	else if (mixedLength == 14)
	{
		var weights = [2, 4, 8, 5, 0, 9, 7, 3, 6, 1, 2, 4, 8];
		controlSum = checkControlSum(value, weights, 11, true);
	}
	if (!controlSum)
	{
		return false;
	}
	return true;
}, 'Niepoprawny numer REGON!');

function checkControlSum(str, weights, modulo, allow_high)
{
	str = str.replace(/[^\d]/g,"");
	if(!str.length) return false;

	var nsize = str.length;
	var j = 0, sum = 0, control = 0;
	var csum = str.substring(nsize - 1);

	for (var i = 0; i < nsize - 1; i++)
	{
			j = parseInt(str.charAt(i));
			sum += j * weights[i];
	}
	if(sum == 0 || str.length < 9)
	{
		return false;
	}
	control = sum % modulo;

	if(allow_high == false) {
			control = 10 - control;
	}

	if (control == 10) {
		control = 0;
	}
	return control == csum;
}

Date.prototype.after = function(p_date) {
    return this.valueOf() > p_date.valueOf();
}

Date.prototype.before = function(p_date) {
    return this.valueOf() < p_date.valueOf();
}

Date.prototype.equal = function(p_date) {
    return this.valueOf() == p_date.valueOf();
}

function NetartPeselAnalyzer(p_PESEL, p_now) {

  this.PESEL = null;

  this.now = null;

  this.minAllowAge = 13;

  this.maxAllowAge = 100;

  this.testedBornDate = null;

  this.minBornDate = null;

  this.maxBornDate = null;

  this.isAdult = function() {
    var bornDate = this.testedBornDate.getCalBornDate();
    if (bornDate == null) {
      return false;
    }

    if (bornDate.after(this.now)) {
      return false;
    }

    var enoughYoung = this.testedBornDate.after(this.minBornDate);
    var enoughOld = this.testedBornDate.before(this.maxBornDate);

    return enoughYoung && enoughOld;
  }

  this.getMinAllowBornDate = function() {
    var minAllowBornDate = null;
    if (this.maxAllowAge != null) {
      minAllowBornDate = cloneDate(this.now);
      minAllowBornDate.setFullYear(minAllowBornDate.getFullYear() - this.maxAllowAge);
      minAllowBornDate = this.truncateToDay(minAllowBornDate);
    }
    return minAllowBornDate;
  }

  this.getMaxAllowBornDate = function() {
    var maxAllowBornDate = null;
    if (this.minAllowAge != null) {
      maxAllowBornDate = cloneDate(this.now);
      maxAllowBornDate.setFullYear(maxAllowBornDate.getFullYear() - this.minAllowAge);
      maxAllowBornDate = this.truncateToDay(maxAllowBornDate);
    }
    return maxAllowBornDate;
  }

  this.isValid = function() {
    var weights = [1,3,7,9,1,3,7,9,1,3];
    if (this.PESEL == null || !checkControlSum(this.PESEL, weights, 10, false)) {
      return false;
    }
    return this.isAdult();
  }

  this.truncateToDay = function(date) {
    var pB = cloneDate(date);
    pB.setHours(0);
    pB.setMinutes(0);
    pB.setSeconds(0);
    return pB;
  }

  this.getPESEL = function() {
    return PESEL;
  }

  this.getNow = function() {
    return now;
  }

  this.setNow = function(now) {
    this.now = now;
  }

  this.getMinAllowAge = function() {
    return minAllowAge;
  }

  this.getMaxAllowAge = function() {
    return maxAllowAge;
  }

  
    this.PESEL = p_PESEL.replace("\\D*", "");
    this.now = p_now;

    this.testedBornDate = new BornDate(peselToCalDate(this.PESEL));
    this.minBornDate = new BornDate(this.getMinAllowBornDate());
    this.maxBornDate = new BornDate(this.getMaxAllowBornDate());
};


function BornDate(p_date) {

    var calBornDate = null;

  this.after = function(p_bornDate) {
    var after = false;
    var checkCalendarDate = p_bornDate.getCalBornDate();
    if (this.calBornDate != null && checkCalendarDate != null) {
      after = this.calBornDate.after(checkCalendarDate);
    }
    return after;
  }

  this.before = function(p_bornDate) {
    var before= false;
    var checkCalendarDate = p_bornDate.getCalBornDate();
    if (this.calBornDate != null && checkCalendarDate != null) {
      before = this.calBornDate.before(checkCalendarDate);
    }
    return before;
  }

  this.getCalBornDate = function() {
    return this.calBornDate;
  }

  this.setCalBornDate = function(calBornDate) {
    this.calBornDate = calBornDate;
  }

  this.calBornDate = p_date;
}


function peselToCalDate(p_pesel) {

    var calBornDate = null;
    var dayDigit = p_pesel.substring(4, 6);
    var mountDigit = p_pesel.substring(2, 4);
    var yearDigit = p_pesel.substring(0, 2);
    var day = parseInt(dayDigit);
    var month = -1;
    var monthCode = parseInt(mountDigit);
    var year = -1;

    if (monthCode>=1 && monthCode<=12) {
      month = monthCode;
      yearDigit = "19" + yearDigit;
    } else if (monthCode>=81 && monthCode<=92) {
      month = monthCode - 80;
      yearDigit = "18" + yearDigit;
    } else if (monthCode>=21 && monthCode<=32) {
      month = monthCode - 20;
      yearDigit = "20" + yearDigit;
    } else if (monthCode>=41 && monthCode<=52) {
      month = monthCode - 40;
      yearDigit = "21" + yearDigit;
    } else if (monthCode>=61 && monthCode<=72) {
      month = monthCode - 60;
      yearDigit = "22" + yearDigit;
    }

	// Date object's months start from 0
    month--;

    var sMount = month.toString();

    if (sMount.length == 1) {
      sMount = "0" + sMount;
    }

    year = parseInt(yearDigit);
    return new Date(year, sMount, dayDigit);
}


function cloneDate(p_date) {

    return new Date(p_date);
}

function canChangeToPoland(value, element, params) {
	var $relatedValue = $(params[0]).val();
	var $isProperStatus = $(params[1]).is(':checked') ? true : false;
	
	if ($relatedValue.length == 0 && value == 'pl' && $isProperStatus) return false;
	return true;
}

/**
* Return true, if the value is a valid vehicle identification number (VIN).
*
* Works with all kind of text inputs.
*
* @example <input type="text" size="20" name="VehicleID" class="{required:true,vinUS:true}" />
* @desc Declares a required input element whose value must be a valid vehicle identification number.
*
* @name jQuery.validator.methods.vinUS
* @type Boolean
* @cat Plugins/Validate/Methods
*/
jQuery.validator.addMethod(
	"vinUS",
	function(v){
		if (v.length != 17)
			return false;
		var i, n, d, f, cd, cdv;
		var LL    = ["A","B","C","D","E","F","G","H","J","K","L","M","N","P","R","S","T","U","V","W","X","Y","Z"];
		var VL    = [1,2,3,4,5,6,7,8,1,2,3,4,5,7,9,2,3,4,5,6,7,8,9];
		var FL    = [8,7,6,5,4,3,2,10,0,9,8,7,6,5,4,3,2];
		var rs    = 0;
		for(i = 0; i < 17; i++){
		    f = FL[i];
		    d = v.slice(i,i+1);
		    if(i == 8){
		        cdv = d;
		    }
		    if(!isNaN(d)){
		        d *= f;
		    }
		    else{
		        for(n = 0; n < LL.length; n++){
		            if(d.toUpperCase() === LL[n]){
		                d = VL[n];
		                d *= f;
		                if(isNaN(cdv) && n == 8){
		                    cdv = LL[n];
		                }
		                break;
		            }
		        }
		    }
		    rs += d;
		}
		cd = rs % 11;
		if(cd == 10){cd = "X";}
		if(cd == cdv){return true;}
		return false;
	},
	"The specified vehicle identification number (VIN) is invalid."
);

/**
  * Return true, if the value is a valid date, also making this formal check dd/mm/yyyy.
  *
  * @example jQuery.validator.methods.date("01/01/1900")
  * @result true
  *
  * @example jQuery.validator.methods.date("01/13/1990")
  * @result false
  *
  * @example jQuery.validator.methods.date("01.01.1900")
  * @result false
  *
  * @example <input name="pippo" class="{dateITA:true}" />
  * @desc Declares an optional input element whose value must be a valid date.
  *
  * @name jQuery.validator.methods.dateITA
  * @type Boolean
  * @cat Plugins/Validate/Methods
  */
jQuery.validator.addMethod(
	"dateITA",
	function(value, element) {
		var check = false;
		var re = /^\d{1,2}\/\d{1,2}\/\d{4}$/;
		if( re.test(value)){
			var adata = value.split('/');
			var gg = parseInt(adata[0],10);
			var mm = parseInt(adata[1],10);
			var aaaa = parseInt(adata[2],10);
			var xdata = new Date(aaaa,mm-1,gg);
			if ( ( xdata.getFullYear() == aaaa ) && ( xdata.getMonth () == mm - 1 ) && ( xdata.getDate() == gg ) )
				check = true;
			else
				check = false;
		} else
			check = false;
		return this.optional(element) || check;
	},
	"Please enter a correct date"
);

jQuery.validator.addMethod("dateNL", function(value, element) {
		return this.optional(element) || /^\d\d?[\.\/-]\d\d?[\.\/-]\d\d\d?\d?$/.test(value);
	}, "Vul hier een geldige datum in."
);

jQuery.validator.addMethod("time", function(value, element) {
	return this.optional(element) || /^([01]\d|2[0-3])(:[0-5]\d){0,2}$/.test(value);
}, "Please enter a valid time, between 00:00 and 23:59");
jQuery.validator.addMethod("time12h", function(value, element) {
	return this.optional(element) || /^((0?[1-9]|1[012])(:[0-5]\d){0,2}(\ [AP]M))$/i.test(value);
}, "Please enter a valid time, between 00:00 am and 12:00 pm");

/**
 * matches US phone number format
 *
 * where the area code may not start with 1 and the prefix may not start with 1
 * allows '-' or ' ' as a separator and allows parens around area code
 * some people may want to put a '1' in front of their number
 *
 * 1(212)-999-2345
 * or
 * 212 999 2344
 * or
 * 212-999-0983
 *
 * but not
 * 111-123-5434
 * and not
 * 212 123 4567
 */
jQuery.validator.addMethod("phoneUS", function(phone_number, element) {
    phone_number = phone_number.replace(/\s+/g, "");
	return this.optional(element) || phone_number.length > 9 &&
		phone_number.match(/^(1-?)?(\([2-9]\d{2}\)|[2-9]\d{2})-?[2-9]\d{2}-?\d{4}$/);
}, "Please specify a valid phone number");

jQuery.validator.addMethod('phoneUK', function(phone_number, element) {
return this.optional(element) || phone_number.length > 9 &&
phone_number.match(/^(\(?(0|\+44)[1-9]{1}\d{1,4}?\)?\s?\d{3,4}\s?\d{3,4})$/);
}, 'Please specify a valid phone number');

jQuery.validator.addMethod('mobileUK', function(phone_number, element) {
return this.optional(element) || phone_number.length > 9 &&
phone_number.match(/^((0|\+44)7(5|6|7|8|9){1}\d{2}\s?\d{6})$/);
}, 'Please specify a valid mobile number');

// TODO check if value starts with <, otherwise don't try stripping anything
jQuery.validator.addMethod("strippedminlength", function(value, element, param) {
	return jQuery(value).text().length >= param;
}, jQuery.validator.format("Please enter at least {0} characters"));

// same as email, but TLD is optional
jQuery.validator.addMethod("email2", function(value, element, param) {
	return this.optional(element) || /^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)*(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i.test(value);
}, jQuery.validator.messages.email);

// same as url, but TLD is optional
jQuery.validator.addMethod("url2", function(value, element, param) {
	return this.optional(element) || /^(https?|ftp):\/\/(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)*(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(\#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i.test(value);
}, jQuery.validator.messages.url);

// NOTICE: Modified version of Castle.Components.Validator.CreditCardValidator
// Redistributed under the the Apache License 2.0 at http://www.apache.org/licenses/LICENSE-2.0
// Valid Types: mastercard, visa, amex, dinersclub, enroute, discover, jcb, unknown, all (overrides all other settings)
jQuery.validator.addMethod("creditcardtypes", function(value, element, param) {

	if (/[^0-9-]+/.test(value))
		return false;

	value = value.replace(/\D/g, "");

	var validTypes = 0x0000;

	if (param.mastercard)
		validTypes |= 0x0001;
	if (param.visa)
		validTypes |= 0x0002;
	if (param.amex)
		validTypes |= 0x0004;
	if (param.dinersclub)
		validTypes |= 0x0008;
	if (param.enroute)
		validTypes |= 0x0010;
	if (param.discover)
		validTypes |= 0x0020;
	if (param.jcb)
		validTypes |= 0x0040;
	if (param.unknown)
		validTypes |= 0x0080;
	if (param.all)
		validTypes = 0x0001 | 0x0002 | 0x0004 | 0x0008 | 0x0010 | 0x0020 | 0x0040 | 0x0080;

	if (validTypes & 0x0001 && /^(51|52|53|54|55)/.test(value)) { //mastercard
		return value.length == 16;
	}
	if (validTypes & 0x0002 && /^(4)/.test(value)) { //visa
		return value.length == 16;
	}
	if (validTypes & 0x0004 && /^(34|37)/.test(value)) { //amex
		return value.length == 15;
	}
	if (validTypes & 0x0008 && /^(300|301|302|303|304|305|36|38)/.test(value)) { //dinersclub
		return value.length == 14;
	}
	if (validTypes & 0x0010 && /^(2014|2149)/.test(value)) { //enroute
		return value.length == 15;
	}
	if (validTypes & 0x0020 && /^(6011)/.test(value)) { //discover
		return value.length == 16;
	}
	if (validTypes & 0x0040 && /^(3)/.test(value)) { //jcb
		return value.length == 16;
	}
	if (validTypes & 0x0040 && /^(2131|1800)/.test(value)) { //jcb
		return value.length == 15;
	}
	if (validTypes & 0x0080) { //unknown
		return true;
	}
	return false;
}, "Please enter a valid credit card number.");

jQuery.validator.addMethod("ipv4", function(value, element, param) {
    return this.optional(element) || /^(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/i.test(value);
}, "Please enter a valid IP v4 address.");

jQuery.validator.addMethod("ipv6", function(value, element, param) {
    return this.optional(element) || /^((([0-9A-Fa-f]{1,4}:){7}[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){6}:[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){5}:([0-9A-Fa-f]{1,4}:)?[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){4}:([0-9A-Fa-f]{1,4}:){0,2}[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){3}:([0-9A-Fa-f]{1,4}:){0,3}[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){2}:([0-9A-Fa-f]{1,4}:){0,4}[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){6}((\b((25[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b)\.){3}(\b((25[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b))|(([0-9A-Fa-f]{1,4}:){0,5}:((\b((25[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b)\.){3}(\b((25[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b))|(::([0-9A-Fa-f]{1,4}:){0,5}((\b((25[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b)\.){3}(\b((25[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b))|([0-9A-Fa-f]{1,4}::([0-9A-Fa-f]{1,4}:){0,5}[0-9A-Fa-f]{1,4})|(::([0-9A-Fa-f]{1,4}:){0,6}[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){1,7}:))$/i.test(value);
}, "Please enter a valid IP v6 address.");

/**
  * Return true if the field value matches the given format RegExp
  *
  * @example jQuery.validator.methods.pattern("AR1004",element,/^AR\d{4}$/)
  * @result true
  *
  * @example jQuery.validator.methods.pattern("BR1004",element,/^AR\d{4}$/)
  * @result false
  *
  * @name jQuery.validator.methods.pattern
  * @type Boolean
  * @cat Plugins/Validate/Methods
  */
jQuery.validator.addMethod("pattern", function(value, element, param) {
    return this.optional(element) || param.test(value);
}, "Invalid format.");

