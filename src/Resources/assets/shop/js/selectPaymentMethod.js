const pblMethodsWrapper = document.querySelector('.bb-online-payment-wrapper-child')

const connectListeners = () => {
    const pblOptionCheckbox = document.querySelector('#choice-pbl')
    const notPblOptionCheckboxesMain = document.querySelectorAll('#choice-blik , #choice-ing , #choice-card')
    
    notPblOptionCheckboxesMain.forEach(checkbox => {
        checkbox.addEventListener('change', (e) => {
            pblMethodsWrapper.classList.add('disabled')
        });
    });

    pblOptionCheckbox.addEventListener('change', (e) => {
        pblMethodsWrapper.classList.toggle('disabled')
    });
    
}

const turnOnListener = () => {
    if (!pblMethodsWrapper) {
        return;
    }
    connectListeners();
};

turnOnListener();
