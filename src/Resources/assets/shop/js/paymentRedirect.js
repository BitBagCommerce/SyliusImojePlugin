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
        this.pblCheckboxesChildren = document.querySelectorAll('.online-payment__input-pbl-child')
    }

    init() {
        if (this.config && typeof this.config !== 'object') {
            throw new Error('BitBag - PaymentRedirect - given config is not valid - expected object');
        }

        this._connectListeners();
    }

    _connectListeners = () => {
        const blikCheckbox = document.querySelector(this.finalConfig.blikId)
        const pblCheckbox = document.querySelector(this.finalConfig.pblId)
        const ingCheckbox = document.querySelector(this.finalConfig.ingId)
        const path = document.querySelector('[data-bb-path-inicialize]').dataset.bbPathInicialize;
        const orderId = document.querySelector('[data-bb-order-id]').dataset.bbOrderId;
        const cardCheckbox = document.querySelector(this.finalConfig.cardId);
        const nextStepButton = document.querySelector('.data-bb-is-payment-button')


        blikCheckbox.addEventListener('click', (e) => {
            const form = document.querySelector('.ui.loadable.form')

            const input = document.createElement('div');
            input.innerHTML = `
                    <input type="number" class='js-blik-input'/>
            `;
            form.appendChild(input)
        });

        nextStepButton.addEventListener('click', (e) => {
            e.preventDefault();
            if (cardCheckbox.checked) {
                performAction(true)
            } else if (pblCheckbox.checked) {
                console.log('pbl');
                const checkedElementValue = document.querySelector('.online-payment__input-pbl-child:checked').value
                window.location.pathname = `${path}/${orderId}/${checkedElementValue}`;
            } else if (blikCheckbox.checked) {
                const blikNumber = document.querySelector('.js-blik-input').value;
                window.location.pathname = `${path}/${orderId}/blik/${blikNumber}`;
            } else if (ingCheckbox.checked) {
                window.location.pathname = `${path}/${orderId}/ing/`;
            } else {
                const form = document.getElementById("theForm");
                form.submit();
            }
        });
    }
}

export default PaymentRedirect;
