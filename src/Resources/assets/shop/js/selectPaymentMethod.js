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
            payLaterId: '#choice-imoje_paylater',
        };
        this.finalConfig = {
            ...this.defaultConfig,
            ...config
        };
        this.pblMethodsWrapper = document.querySelector('.bb-online-payment-wrapper-child');
        this.payLaterMethodsWrapper = document.querySelector('.bb-online-payment-wrapper-pay-later');
        this.pblCheckboxesChildren = document.querySelectorAll('.online-payment__input-pbl-child');
        this.payLaterCheckboxesChildren = document.querySelectorAll('.online-payment__input-pay-later-child');
    }

    init() {
        if (this.config && typeof this.config !== 'object') {
            throw new Error('BitBag - SelectPaymentMethod - given config is not valid - expected object');
        }

        this._connectListeners();
    }

    _turnOffNotNeededCheckboxes = checkboxesArray => {
        checkboxesArray.forEach(checkbox => {
            checkbox.checked = false;
        });
    }

    _checkIfAnyChecked = checkboxesArray => {
        return checkboxesArray.some(checkbox => checkbox.checked === true);
    }
    
    _resetRadioButtons = () => {
      const pblMethodRadioButtons = document.querySelectorAll('input[class="online-payment__input-pbl-child"]:checked');
      const payLaterMethodRadioButtons = document.querySelectorAll('input[class="online-payment__input-pay-later-child"]:checked');

      if (event.target.value !== 'pbl') {
          pblMethodRadioButtons.forEach(pblMethodRadioButton => { pblMethodRadioButton.checked = false} );
      }
      if (event.target.value !== 'imoje_paylater') {
          payLaterMethodRadioButtons.forEach(payLaterMethodRadioButton => { payLaterMethodRadioButton.checked = false} );
      }
    }

    _connectListeners = () => {
        const paymentMethodsWrapper = document.querySelector('.bb-online-payment-wrapper');
        const IngCheckbox = document.querySelector('.ing-payments');
        const otherThanIngCheckboxes = document.querySelectorAll('[value="cash_on_delivery"], [value="bank_transfer"]');
        const pblOptionCheckbox = document.querySelector(this.finalConfig.pblId);
        const payLaterOptionCheckbox = document.querySelector(this.finalConfig.payLaterId);
        const nextStepButton = document.getElementById('next-step');
        const ingPayment = document.querySelector(this.finalConfig.ingId);
        const paymentMethodRadioButtons = document.querySelectorAll('.online-payment__input');
        const notPblOptionCheckboxesMain = [...document.querySelectorAll(
            `${ this.finalConfig.blikId },
            ${ this.finalConfig.ingId },
            ${ this.finalConfig.cardId },
            ${ this.finalConfig.payLaterId }`
        )];
        const notPayLaterOptionCheckboxesMain = [...document.querySelectorAll(
            `${ this.finalConfig.blikId },
            ${ this.finalConfig.ingId },
            ${ this.finalConfig.cardId },
            ${ this.finalConfig.pblId }`
        )];

        if (!pblOptionCheckbox && notPblOptionCheckboxesMain.length === 0) {
            const ingGateway = IngCheckbox.closest('.item');

            ingGateway.style.display = 'none';
            return;
        }

        IngCheckbox.click();
        otherThanIngCheckboxes[0].click();
        IngCheckbox.click();
        ingPayment.click();

        notPblOptionCheckboxesMain.forEach(checkbox => {
            checkbox.addEventListener('change', () => {
                this.pblMethodsWrapper.classList.add(this.finalConfig.disabledClass);
            });
        });

        notPayLaterOptionCheckboxesMain.forEach(checkbox => {
            checkbox.addEventListener('change', () => {
                this.payLaterMethodsWrapper.classList.add(this.finalConfig.disabledClass);
            });
        });

        if (pblOptionCheckbox !== null) {
            pblOptionCheckbox.addEventListener('change', () => {
                this.pblMethodsWrapper.classList.toggle(this.finalConfig.disabledClass);
                this.pblCheckboxesChildren[0].checked = true;
            });
        }

        if (payLaterOptionCheckbox !== null) {
            payLaterOptionCheckbox.addEventListener('change', () => {
                this.payLaterMethodsWrapper.classList.toggle(this.finalConfig.disabledClass);
                this.payLaterCheckboxesChildren[0].checked = true;
            });
        }


        IngCheckbox.addEventListener('change', () => {
            paymentMethodsWrapper.classList.toggle('disabled');
        });

        otherThanIngCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', () => {
                paymentMethodsWrapper.classList.add('disabled');
            });
        });

        IngCheckbox.addEventListener('change', () =>  ingPayment.checked = true );

        nextStepButton.addEventListener('click', () => {
            if (pblOptionCheckbox !== null) {
                if (pblOptionCheckbox.checked && IngCheckbox.checked) {
                    return;
                }
            } else if (IngCheckbox.checked && this._checkIfAnyChecked(notPblOptionCheckboxesMain)) {
                this._turnOffNotNeededCheckboxes(this.pblCheckboxesChildren);
            }

            otherThanIngCheckboxes.forEach(checkbox => {
                if (checkbox.checked) {
                    this._turnOffNotNeededCheckboxes(notPblOptionCheckboxesMain.concat(pblOptionCheckbox));
                    this._turnOffNotNeededCheckboxes(this.pblCheckboxesChildren);
                    this.pblMethodsWrapper.classList.add('disabled');
                    paymentMethodsWrapper.classList.add('disabled');
                }
            });
        });
        
        paymentMethodRadioButtons.forEach(paymentMethodRadioButton => {
            paymentMethodRadioButton.addEventListener('change', this._resetRadioButtons);
        })
    }
}

export default SelectPaymentMethod;
