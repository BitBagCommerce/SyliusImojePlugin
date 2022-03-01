import { PaymentRedirect } from './paymentRedirect';

const isPaymentButton = document.querySelector('.data-bb-is-payment-button')

const turnOnListener = () => {
    if (!isPaymentButton) {
        return;
    }
    
    new PaymentRedirect().init();
};

turnOnListener();
