const pblMethodsWrapper = document.querySelector('.bb-online-payment-wrapper-child')

const connectListeners = () => {
    const pblOptionCheckbox = document.querySelector('#choice-0')
    const notPblOptionCheckboxesMain = document.querySelectorAll('#choice-21 , #choice-22 , #choice-23')
    
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
