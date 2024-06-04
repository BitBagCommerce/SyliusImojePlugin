export const performAction = async (isAfterPayment = false) => {

    const paymentMethod = isAfterPayment
        ? ''
        : document.querySelector('[data-bb-is-payment-method]').dataset.bbIsPaymentMethod;

    const isImoje = isAfterPayment
        ? ''
        : document.querySelector('[data-bb-is-imoje-method]').dataset.bbIsImojeMethod;

    if ('card' === paymentMethod && 'imojePaymentMethods' === isImoje || isAfterPayment) {
        const orderId = document.querySelector('[data-bb-order-id]').dataset.bbOrderId;

        const url = `/payment/oneclick/${orderId}`;

        try {
            const response = await fetch(url);
            const data = await response.json();
            const script = document.createElement('script');

            if (data.isProd) {
                script.src = 'https://paywall.imoje.pl/js/widget.min.js';
            } else {
                script.src = 'https://sandbox.paywall.imoje.pl/js/widget.min.js';
            }

            script.id = 'imoje-widget__script';
            script.dataset.merchantId = data.merchantId;
            script.dataset.serviceId = data.serviceId;
            script.dataset.amount = data.amount;
            script.dataset.currency = data.currency;
            script.dataset.orderId = data.orderId;
            script.dataset.customerId = data.customerId;
            script.dataset.customerFirstName = data.customerFirstName;
            script.dataset.customerLastName = data.customerLastName;
            script.dataset.customerEmail = data.customerEmail;
            script.dataset.urlSuccess = data.urlSuccess;
            script.dataset.urlFailure = data.urlFailure;
            script.dataset.signature = data.signature;
            document.querySelector('head').appendChild(script);
        } catch (error) {
            console.error(error);
        }
    }
}

const widgetImoje = document.querySelector('.js-widget-imoje-action');
const turnOnListener = () => {
    if (widgetImoje) {
        widgetImoje.addEventListener('click', e => {
            e.preventDefault();
            performAction();
        });
    }
};

turnOnListener();
