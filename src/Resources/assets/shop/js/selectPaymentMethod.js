export class SelectPaymentMethod {
    constructor(
        config = {},
    ) {
        this.config = config;
        this.defaultConfig = {
            disabledClass: 'disabled',
        };
        this.finalConfig = {
            ...this.defaultConfig,
            ...config
        };
        this.ingPaymentsWrapper = document.querySelector('.bb-online-payment-wrapper');
        this.additionalPaymentsWrappers = document.querySelectorAll('.bb-online-payment-wrapper-child');
        this.paymentMethodRadios = document.querySelectorAll('input[name$="][method]"]');
        this.ingPaymentMethodRadios = document.querySelectorAll('.online-payment__input');
    }

    init() {
        if (this.config && typeof this.config !== 'object') {
            throw new Error('BitBag - SelectPaymentMethod - given config is not valid - expected object');
        }

        this._connectListeners();

        if (document.querySelector('.ing-payments:checked')) {
            this._openPaymentsWrapper();
        }
    }

    _openPaymentsWrapper() {
        this.ingPaymentsWrapper.classList.remove(this.finalConfig.disabledClass);
        this.ingPaymentMethodRadios[0].click();
    }

    _closePaymentsWrapper() {
        this.ingPaymentsWrapper.classList.add(this.finalConfig.disabledClass);

        this.ingPaymentMethodRadios.forEach(paymentMethodRadio => {
            paymentMethodRadio.checked = false;
        });

        this._closeAdditionalPaymentsWrappers();
    }

    _openAdditionalPaymentsWrapper(name) {
        const additionalPaymentsWrapper = document.getElementById(name);

        if (additionalPaymentsWrapper) {
            additionalPaymentsWrapper.classList.remove(this.finalConfig.disabledClass);

            additionalPaymentsWrapper.querySelector('input').checked = true;
        }
    }

    _closeAdditionalPaymentsWrappers() {
        this.additionalPaymentsWrappers.forEach(additionalPaymentsWrapper => {
            additionalPaymentsWrapper.classList.add(this.finalConfig.disabledClass);

            const additionalPaymentsRadios = additionalPaymentsWrapper.querySelectorAll('.online-payment__input-child');

            additionalPaymentsRadios.forEach(additionalPaymentRadio => {
                additionalPaymentRadio.checked = false;
            });
        });
    }

    _connectListeners() {
        this.paymentMethodRadios.forEach(paymentMethodRadio => {
            paymentMethodRadio.addEventListener('change', () => {
                if (paymentMethodRadio.classList.contains('ing-payments')) {
                    this._openPaymentsWrapper();
                } else {
                    this._closePaymentsWrapper();
                }
            });
        });

        this.ingPaymentMethodRadios.forEach(paymentMethodRadio => {
            paymentMethodRadio.addEventListener('change', () => {
                this._closeAdditionalPaymentsWrappers();
                this._openAdditionalPaymentsWrapper(paymentMethodRadio.dataset.target);
            });
        });
    }
}

export default SelectPaymentMethod;
