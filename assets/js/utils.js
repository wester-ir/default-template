function lockElem(elem) {
    $(elem).attr('data-locked', 'true');
}

function unlockElem(elem) {
    $(elem).attr('data-locked', 'false');
}

function random(length = 10) {
    let result = '';
    const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    const charactersLength = characters.length;
    let counter = 0;

    while (counter < length) {
        result += characters.charAt(Math.floor(Math.random() * charactersLength));
        counter += 1;
    }

    return result;
}

function pad(num) {
    num = num.toString();

    return ('0' + num).substr(num.length - 1);
}

function ready(fn) {
    if (document.readyState !== 'loading') {
        fn();

        return;
    }

    document.addEventListener('DOMContentLoaded', fn);
}

function formatNumber(number, opt = null) {
    opt = Object.assign({
        separator: ',',
        allowEmpty: false,
    }, opt);

    // Allow empty
    if (opt.allowEmpty && empty(number)) {
        return '';
    }

    var str = toNumber(number).toString();

    // Separate whole number and decimal part
    const parts = str.split('.');

    // Format whole number part with commas
    const wholeNumber = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, opt.separator);

    // Format decimal part with commas
    const decimal = parts[1] ? parts[1] : false;

    return decimal ? `${wholeNumber}.${decimal}` : wholeNumber;
}

function formatInputNumber(e) {
    var val = e.target.value.trim();
    var allowEmpty = e.target.getAttribute('data-empty') !== null;
    var allowNegative = e.target.getAttribute('data-negative') !== null;

    // Allow Empty
    if (allowEmpty && empty(val)) {
        e.target.value = '';
        return '';
    }

    // Negative
    if (allowNegative && (val === '0-' || val === '-' || val === '-0')) {
        e.target.value = '-';

        return 0;
    }

    // Don't allow negative numbers
    if (! allowNegative && val.indexOf('-') > -1) {
        val = val.replaceAll('-', '');
    }

    const isFloat = val.endsWith('.');
    var allowFloatNumber = e.target.getAttribute('data-float');
    allowFloatNumber = allowFloatNumber === null ? false : allowFloatNumber;

    val = formatNumber(val);

    if (isFloat && toBoolean(allowFloatNumber)) {
        val = val + '.';
    }

    e.target.value = val;

    return toNumber(val);
}

function toNumber(n) {
    n = String(n).replaceAll(',', '');
    n = Number(n);

    if (Number.isNaN(n)) {
        return 0;
    }

    return n;
}

function onlyKeys(obj, keys) {
    var newObj = {};

    keys.forEach(key => {
        if (obj.hasOwnProperty(key)) {
            newObj[key] = obj[key];
        }
    });

    return newObj;
}

function exceptKeys(obj, keys) {
    var newObj = {};

    Object.keys(obj).forEach(key => {
        if (keys.indexOf(key) === -1) {
            newObj[key] = obj[key];
        }
    });

    return newObj;
}

const form = window.form = {
    setFieldErrors(formSelector, id, errors) {
        const elem = document.createElement('ul');

        elem.classList.add('input-errors');

        errors.forEach(function (error) {
            elem.innerHTML+= '<li>'+ error +'</li>';
        });

        $(elem).insertAfter(formSelector +' #'+ id);
        $(formSelector +' .form-control[data-form-field-id="'+ id +'"]').attr('data-danger', 'true');
    },
    setFormErrors(selector, errors, keys = {}) {
        Object.keys(errors).forEach(function (key) {
            const arr = errors[key];

            if (keys.hasOwnProperty(key)) {
                key = keys[key];
            }

            form.setFieldErrors(selector, key, arr);
        });
    },
    resetFormErrors(selector) {
        $(selector).find('.input-errors').remove()
        $(selector).find('.form-control').removeAttr('data-danger');
    },
    reset(selector) {
        $(selector)[0].reset();
    },
    catch(e, overwrite = {}) {
        const status = e.request.status;

        if (status in overwrite) {
            toast.error(overwrite[status]);
            return;
        }

        switch (status) {
            case 429:
                toast.error('لطفاً بعدا امتحان کنید.');
            break;
            case 422:
                toast.error('لطفاً خطاهای فرم را رفع کنید.');
            break;
            case 403:
                toast.error('عملیات مورد نظر غیرمجاز می باشد.');
            break;
        }
    }
};

const requestStore = {
    addToCart(combinationId) {
        return axios.post(endpoints.addToCart, {
            product_combination_id: combinationId,
        });
    },
    updateCartItem(combinationId, newQuantity) {
        return axios.post(endpoints.updateCartItem, {
            product_combination_id: combinationId,
            new_quantity: newQuantity,
        });
    },
    removeCartItem(combinationId) {
        return axios.delete(endpoints.removeCartItem, {
            data: {
                product_combination_id: combinationId,
            },
        });
    },
    clear() {
        return axios.delete(endpoints.clearCart);
    },
};

const elements = {
    setItemsTotalQuantity(quantity) {
        $('[data-role="items-total-quantity-count"]').html(quantity);
    }
};

function scrollToTab(tabId) {
    const tab = document.getElementById(tabId);

    if (tab) {
        tab.scrollIntoView({ block: 'nearest', inline: 'start' });
    }
}
