export class SelectPaymentMethod {
    constructor(
        config = {},
    ) {
        this.config = config;
        this.defaultConfig = {
            paymentTargetsClass: '.bb-pbl-methods',
            disabledClass: 'disabled',
            pblId: '#choice-pbl',
            blikId: '#choice-blik',
            ingId: '#choice-ing',
            cardId: '#choice-card',
        };
        this.finalConfig = {
            ...this.defaultConfig,
            ...config    
        };
        this.pblMethodsWrapper = document.querySelector('.bb-online-payment-wrapper-child');
    }

    init() {
        if (this.config && typeof this.config !== 'object') {
            throw new Error('BitBag - SelectPaymentMethod - given config is not valid - expected object');
        }

        this.connectListeners();
    }

    connectListeners = () => {
        const pblOptionCheckbox = document.querySelector(this.finalConfig.pblId);
        const notPblOptionCheckboxesMain = document.querySelectorAll(
            `${ this.finalConfig.blikId } , ${ this.finalConfig.ingId } , ${ this.finalConfig.cardId }`
        );

        notPblOptionCheckboxesMain.forEach(checkbox => {
            checkbox.addEventListener('change', () => {
                this.pblMethodsWrapper.classList.add(this.finalConfig.disabledClass);
            });
        });

        pblOptionCheckbox.addEventListener('change', () => {
            this.pblMethodsWrapper.classList.toggle(this.finalConfig.disabledClass);
        });
    }
}

export default SelectPaymentMethod;
