const paymentMethodHandler = document.querySelector('#sylius_payment_method_gatewayConfig_config_pbl')

const tooglePayment = () => {
    const paymentTargets = document.querySelectorAll('.bb-pbl-methods')
    paymentTargets.forEach(checkbox => {
        checkbox.classList.toggle('bb-payment-disabled')
        // checkbox.closest()
    });
}

const connectListeners = () => {
    console.log(paymentMethodHandler)
    paymentMethodHandler.addEventListener('change', (e) => {
        setTimeout( () => {
            e.preventDefault();
            tooglePayment();
        }, 50)
    });
}

const turnOnListener = () => {
    if (!paymentMethodHandler) {
        return;
    }
    connectListeners();
    tooglePayment();
};

turnOnListener();


