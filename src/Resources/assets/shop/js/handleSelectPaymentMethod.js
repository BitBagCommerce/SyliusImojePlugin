import { SelectPaymentMethod } from './selectPaymentMethod';

const pblMethodsWrapper = document.querySelector('.bb-online-payment-wrapper-child')

const turnOnListener = () => {
    if (!pblMethodsWrapper) {
        return;
    }
    new SelectPaymentMethod().init();
};

turnOnListener();
