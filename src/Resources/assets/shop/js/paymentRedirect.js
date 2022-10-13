import { performAction } from '../../sdk_ing/entry';

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
            payLaterId: '#choice-imoje_paylater',
        };
        this.finalConfig = {
            ...this.defaultConfig,
            ...config
        };
        this.pblCheckboxesChildren = document.querySelectorAll('.online-payment__input-pbl-child');
    }

    init() {
        if (this.config && typeof this.config !== 'object') {
            throw new Error('BitBag - PaymentRedirect - given config is not valid - expected object');
        }

        this._connectListeners();
    }

    _connectListeners = () => {
        const blikCheckbox = document.querySelector(this.finalConfig.blikId);
        const pblCheckbox = document.querySelector(this.finalConfig.pblId);
        const payLaterCheckbox = document.querySelector(this.finalConfig.payLaterId);
        const ingCheckbox = document.querySelector(this.finalConfig.ingId);
        const path = document.querySelector('[data-bb-path-inicialize]').dataset.bbPathInicialize;
        const orderId = document.querySelector('[data-bb-order-id]').dataset.bbOrderId;
        const cardCheckbox = document.querySelector(this.finalConfig.cardId);
        const nextStepButton = document.querySelector('.data-bb-is-payment-button');

        if (ingCheckbox) {
            ingCheckbox.addEventListener('click', () => {
                const blikInputWrapper = document.querySelector('.js-blik-input-wrapper');
                if (blikInputWrapper) {
                    blikInputWrapper.classList.add('disabled')
                }
            });
        }

        if (pblCheckbox) {
            pblCheckbox.addEventListener('click', () => {
                const blikInputWrapper = document.querySelector('.js-blik-input-wrapper');
                if (blikInputWrapper) {
                    blikInputWrapper.classList.add('disabled')
                }
            });
        }

        if (payLaterCheckbox) {
            payLaterCheckbox.addEventListener('click', () => {
                const blikInputWrapper = document.querySelector('.js-blik-input-wrapper');
                if (blikInputWrapper) {
                    blikInputWrapper.classList.add('disabled')
                }
            });
        }

        if (cardCheckbox) {
            cardCheckbox.addEventListener('click', () => {
                const blikInputWrapper = document.querySelector('.js-blik-input-wrapper');
                if (blikInputWrapper) {
                    blikInputWrapper.classList.add('disabled')
                }
            });
        }

        if (blikCheckbox) {
            blikCheckbox.addEventListener('click', () => {
                const form = document.querySelector('.ui.loadable.form');
                const input = document.createElement('div');
                const blikInputWrapper = form.querySelector('.js-blik-input-wrapper');


                if (blikInputWrapper) {
                    blikInputWrapper.classList.remove('disabled')
                    return;
                }
                input.innerHTML = `
                        <div class="three wide field removeArrows js-blik-input-wrapper" style="margin-top: 10px">
                                <label>Blik Code</label>
                                <input type="number" class='js-blik-input' maxlength="6"/>
                        </div>
                `;
                form.appendChild(input)
            });
        }

        nextStepButton.addEventListener('click', e => {
            e.preventDefault();
            if (cardCheckbox.checked) {
                performAction(true);
            } else if (pblCheckbox && pblCheckbox.checked) {
                const checkedElementValue = document.querySelector('.online-payment__input-pbl-child:checked').value;
                window.location.pathname = `${path}/${orderId}/${checkedElementValue}`;
            } else if (payLaterCheckbox && payLaterCheckbox.checked) {
                const checkedElementValue = document.querySelector('.online-payment__input-pay-later-child:checked').value;
                window.location.pathname = `${path}/${orderId}/${checkedElementValue}`;
            } else if (blikCheckbox && blikCheckbox.checked) {
                const blikNumber = document.querySelector('.js-blik-input').value;

                window.location.pathname = `${path}/${orderId}/blik/${blikNumber}`;
            } else if (ingCheckbox && ingCheckbox.checked) {
                window.location.pathname = `${path}/${orderId}/ing/`;
            } else {
                const form = document.getElementById("theForm");

                form.submit();
            }
        });
    }
}

export default PaymentRedirect;
