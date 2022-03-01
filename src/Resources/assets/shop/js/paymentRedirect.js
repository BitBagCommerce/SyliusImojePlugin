export class PaymentRedirect {
    constructor(
        config = {},
    ) {
        this.config = config;
        this.defaultConfig = {
            paymentTargetsClass: '.bb-pbl-methods',
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
        this.pblCheckboxesChildren = document.querySelectorAll('.online-payment__input-pbl-child')
    }

    init() {
        if (this.config && typeof this.config !== 'object') {
            throw new Error('BitBag - PaymentRedirect - given config is not valid - expected object');
        }

        this._connectListeners();
    }


    _connectListeners = () => {
        const paymentMethodsWrapper = document.querySelector('.bb-online-payment-wrapper')
        const IngCheckbox = document.querySelector('[value="ing_code"]')
        const otherThanIngCheckboxes = document.querySelectorAll('[value="cash_on_delivery"], [value="bank_transfer"]')
        const pblOptionCheckbox = document.querySelector(this.finalConfig.pblId);

        const nextStepButton = document.querySelector('.data-bb-is-payment-button')
        const notPblOptionCheckboxesMain = [...document.querySelectorAll(
            `${ this.finalConfig.ingId } , ${ this.finalConfig.cardId }`
        )];

        nextStepButton.addEventListener('click', (e) => {
            e.preventDefault();
            console.log('next button')

            if (notPblOptionCheckboxesMain.some(checkbox => checkbox.checked)) {
                console.log('card, blik')
                // window.location.replace("http://stackoverflow.com");
            }else {
                console.log('pbl ing')
            
            }
            
        });
    }
}

export default SelectPaymentMethod;
