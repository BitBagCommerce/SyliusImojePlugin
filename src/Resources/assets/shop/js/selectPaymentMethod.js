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
        this.pblCheckboxesChildren = document.querySelectorAll('.online-payment__input-pbl-child');
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

    _connectListeners = () => {
        const paymentMethodsWrapper = document.querySelector('.bb-online-payment-wrapper');
        const IngCheckbox = document.querySelector('.ing-payments');
        const otherThanIngCheckboxes = document.querySelectorAll('[value="cash_on_delivery"], [value="bank_transfer"]');
        const pblOptionCheckbox = document.querySelector(this.finalConfig.pblId);
        const nextStepButton = document.getElementById('next-step');
        const notPblOptionCheckboxesMain = [...document.querySelectorAll(
            `${ this.finalConfig.blikId },
            ${ this.finalConfig.ingId },
            ${ this.finalConfig.cardId }`
        )];

        IngCheckbox.click();
        otherThanIngCheckboxes[0].click();
        IngCheckbox.click();

        notPblOptionCheckboxesMain.forEach(checkbox => {
            checkbox.addEventListener('change', () => {
                this.pblMethodsWrapper.classList.add(this.finalConfig.disabledClass);
            });
        });

        pblOptionCheckbox.addEventListener('change', () => {
            this.pblMethodsWrapper.classList.toggle(this.finalConfig.disabledClass);
        });

        IngCheckbox.addEventListener('change', () => {
            paymentMethodsWrapper.classList.toggle('disabled');
        });

        otherThanIngCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', () => {
                paymentMethodsWrapper.classList.add('disabled');
            });
        });

        nextStepButton.addEventListener('click', () => {
            if (pblOptionCheckbox.checked && IngCheckbox.checked) {
                return;
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
    }
}

export default SelectPaymentMethod;
