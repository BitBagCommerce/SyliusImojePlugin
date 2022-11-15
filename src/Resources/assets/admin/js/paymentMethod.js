export class PaymentMethod {
    constructor(
        config = {},
    ) {
        this.config = config;
        this.defaultConfig = {
            paymentCheckboxesClass: '.bb-pbl-methods',
            disabledClass: 'bb-payment-disabled'
        };
        this.finalConfig = {
            ...this.defaultConfig,
            ...config
        };
        this.paymentMethodHandler = document.getElementById('sylius_payment_method_gatewayConfig_config_pbl');
        this.saveMethodForm = document.querySelector('[name="sylius_payment_method"]');
        this.paymentCheckboxes = document.querySelectorAll(this.finalConfig.paymentCheckboxesClass);
    }

    init() {
        if (this.config && typeof this.config !== 'object') {
            throw new Error('BitBag - PaymentMethod - given config is not valid - expected object');
        }

        this.connectListeners();
        this.togglePaymentOff();
        this.loadDataInSession();
    }

    togglePayment() {
        this.paymentCheckboxes.forEach(checkbox => {
            checkbox.closest('.required.field').classList.toggle(this.finalConfig.disabledClass);

            if (checkbox.closest('.required.field').classList.contains(this.finalConfig.disabledClass)) {
                this.toggleCheckboxes(this.paymentCheckboxes, false);
                this.paymentMethodHandler.checked = false;
            }
        });
    }

    togglePaymentOff() {
        this.paymentCheckboxes.forEach(checkbox => {
            checkbox.closest('.required.field').classList.toggle(this.finalConfig.disabledClass);
        });
        this.toggleCheckboxes(this.paymentCheckboxes, false);
        this.paymentMethodHandler.checked = false;
    }

    toggleCheckboxes(checkboxes, bool) {
        checkboxes.forEach(checkbox => {
            checkbox.checked = bool;
        });
    }

    toggleMarginOff(checkboxes) {
        checkboxes.forEach(checkbox => {
            checkbox.closest('.two.fields').classList.add('bb-pbl-margin-zero');
        });
    }

    handleChildCheckbox() {
        const isAnyChecked = [...this.paymentCheckboxes].some(checkbox => checkbox.checked);

        if (!isAnyChecked) {
            this.paymentMethodHandler.checked = false;
            this.togglePayment();
        }
    }

    storeDataInSession() {
        const sessionData = [...this.paymentCheckboxes].reduce((accumulator, checkbox) => ({
            ...accumulator,
            [checkbox.id]: checkbox.checked
        }), {});

        window.sessionStorage.setItem('checkboxesState', JSON.stringify(sessionData));
    }

    loadDataInSession() {
        const sessionData = JSON.parse(sessionStorage.getItem('checkboxesState'));
        if (sessionData) {
            this.paymentMethodHandler.checked = true;

            this.paymentCheckboxes.forEach(checkbox => {
                checkbox.closest('.required.field').classList.toggle(this.finalConfig.disabledClass);
                checkbox.checked = !!sessionData[checkbox.id];
            });
        }
    }

    connectListeners() {
        this.paymentMethodHandler.addEventListener('change', e => {
            if (this.paymentMethodHandler.checked) {
                this.toggleCheckboxes(this.paymentCheckboxes, true);
            }

            setTimeout( () => {
                e.preventDefault();
                this.togglePayment();
            }, 50);
        });

        this.saveMethodForm.addEventListener('submit', () => {
            this.storeDataInSession();
        });

        this.paymentCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', () => {
                this.handleChildCheckbox(checkbox);
            });
        });
    }
}

export default PaymentMethod;
