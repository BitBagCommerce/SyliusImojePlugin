const paymentMethodHandler = document.querySelector('#sylius_payment_method_gatewayConfig_config_pbl')

const tooglePayment = () => {
    const paymentTargets = document.querySelectorAll('.bb-pbl-methods')
    paymentTargets.forEach(checkbox => {
        checkbox.closest('div div').classList.toggle('bb-payment-disabled')

        if (checkbox.closest('div div').classList.contains('bb-payment-disabled') == true) {
            toggleCheckboxesOFF(paymentTargets)
        }
    });
}

const toggleCheckboxesOFF = (checkboxes) => {
    for (let index = 0; index < checkboxes.length; index++) {
        checkboxes[index].checked = false;    
    }  
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


