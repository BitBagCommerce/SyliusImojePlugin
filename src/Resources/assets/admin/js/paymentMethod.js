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
        this.tooglePaymentOff();
        this.loadDataInSession(); 
    }

    tooglePayment = () => {
        this.paymentCheckboxes.forEach(checkbox => {
            checkbox.closest('.required.field').classList.toggle(this.finalConfig.disabledClass);

            if (checkbox.closest('.required.field').classList.contains(this.finalConfig.disabledClass)) { 
                this.toggleCheckboxesOff(this.paymentCheckboxes);
            }
        });
    }
    
    tooglePaymentOff = () => {
        this.paymentCheckboxes.forEach(checkbox => {
            checkbox.closest('.required.field').classList.toggle(this.finalConfig.disabledClass);
        });
        this.toggleCheckboxesOff(this.paymentCheckboxes);
        this.paymentMethodHandler.checked = false;
    }
    
    toggleCheckboxesOff = checkboxes => { 
        checkboxes.forEach(checkbox => {
            checkbox.checked = false;
        });
    }

    toggleMarginOff = checkboxes => {
        checkboxes.forEach(checkbox => {
            checkbox.closest('.two.fields').classList.add('bb-pbl-margin-zero');
        });
    }

    storeDataInSession = () => { 
        const checkboxesCheckedData = [...this.paymentCheckboxes].map(checkbox => ([checkbox.id, checkbox.checked]))
        const sessionData = Object.fromEntries(checkboxesCheckedData)
        
        window.sessionStorage.setItem("checkboxesState", JSON.stringify(sessionData));
    }

    loadDataInSession = () => {
        const sessionData = JSON.parse(sessionStorage.getItem("checkboxesState"));
        
        if (sessionData) {
            this.paymentMethodHandler.checked = true;

            this.paymentCheckboxes.forEach(checkbox => {
                checkbox.closest('.required.field').classList.toggle(this.finalConfig.disabledClass);
                checkbox.checked = sessionData[checkbox.id] ?? false;
            });
        } else {
            this.toggleMargin(this.paymentCheckboxes);
        }

    }

    connectListeners = () => {
        this.paymentMethodHandler.addEventListener('change', e => {
            setTimeout( () => {
                e.preventDefault();
                this.tooglePayment();          
            }, 50);
        });

        this.saveMethodForm.addEventListener('submit', () => {
            this.storeDataInSession();
        });
    }
}

export default PaymentMethod;
