export class PaymentMethod {
    constructor(
        config = {},
    ) {
        this.config = config;
        this.defaultConfig = {
            paymentTargetsClass: '.bb-pbl-methods',
            disabledClass: 'bb-payment-disabled'
        };
        this.finalConfig = {
            ...this.defaultConfig, 
            ...config
        };
        this.paymentMethodHandler = document.getElementById('sylius_payment_method_gatewayConfig_config_pbl');
    }

    init() {
        if (this.config && typeof this.config !== 'object') {
            throw new Error('BitBag - PaymentMethod - given config is not valid - expected object');
        }

        this.connectListeners();
        this.tooglePaymentOff();
    }

    tooglePayment = () => {
        const paymentTargets = document.querySelectorAll(this.finalConfig.paymentTargetsClass);

        paymentTargets.forEach(checkbox => {
            checkbox.closest('.required.field').classList.toggle(this.finalConfig.disabledClass);
    
            if (checkbox.closest('.required.field').classList.contains(this.finalConfig.disabledClass)) {
                this.toggleCheckboxesOff(paymentTargets);
            }
        });
    }
    
    tooglePaymentOff = () => {
        const paymentTargets = document.querySelectorAll(this.finalConfig.paymentTargetsClass);

        paymentTargets.forEach(checkbox => {
            checkbox.closest('.required.field').classList.toggle(this.finalConfig.disabledClass);
        });
        this.toggleCheckboxesOff(paymentTargets);
        this.paymentMethodHandler.checked = false;
    }
    
    toggleCheckboxesOff = checkboxes => { 
        checkboxes.forEach(checkbox => {
            checkbox.checked = false;
        });
    }
    
    connectListeners = () => {
        this.paymentMethodHandler.addEventListener('change', e => {
            setTimeout( () => {
                e.preventDefault();
                this.tooglePayment();          
            }, 50);
        });
    }
}

export default PaymentMethod;
