import { PaymentRedirect } from './paymentRedirect';

const turnOnListener = () => {
    const isPaymentButton = document.querySelector('.data-bb-is-payment-button');
    
    if (isPaymentButton) {
        new PaymentRedirect().init();
    }
};

turnOnListener();
