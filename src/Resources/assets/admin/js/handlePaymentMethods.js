import { PaymentMethod } from './paymentMethod';

const paymentMethodHandler = document.querySelector('#sylius_payment_method_gatewayConfig_config_pbl');

const turnOnListener = () => {
    if (!paymentMethodHandler) {
        return;
    }
    
    new PaymentMethod().init();
};

turnOnListener();
