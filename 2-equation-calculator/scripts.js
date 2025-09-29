function calculateAbsError() {
    let measured = $('#measured-value').val();
    let theoretical = $('#theoretical-value').val();
    let errorNum = measured - theoretical;

    let absoluteError = Math.abs(errorNum);
    let roundedAbsError = Math.round(absoluteError, 3);

    $('#absolute-error').html(roundedAbsError);
}

function calculateEnergyOfPhoton() {
    let frequency = $('#frequency').val();
    let wavelength = $('#wavelength').val();
    let h = 6.626 * (10**-34);
    let c = 3 * (10**8);
    
    if (frequency == '' && wavelength != '') {
        let energy = (h*c)/wavelength;
        let roundedEnergy = energy.toExponential(3);
        $('#energy').html(roundedEnergy + ' J');
    } else if (wavelength == '' && frequency != '') {
        let energy = h*frequency;
        let roundedEnergy = energy.toExponential(3);
        $('#energy').html(roundedEnergy + ' J');
    } else {
        $('#energy').html('Error. Please input either frequency or wavelength.');
    }
}

function calculatePercentComposition() {
    let elementMass = $('#element-mass').val();
    let compoundMass = $('#compound-mass').val();
    let elementAmount = $('#element-amount').val();
    let elementName = $('#element-name').val();
    
    let percentComposition = ((elementMass*elementAmount)/compoundMass)*100;
    let roundedComposition = Math.round(percentComposition, 2);

    if (percentComposition > 0 && percentComposition <= 100) {
        $('#percent-composition').html(roundedComposition + '% ' + elementName);
    } else {
        $('#percent-composition').html('Error. Please check your input values and try again.');
    }
}

function calculatePercentError() {
    let absError = $('#abs-error').val();
    let trueVal = $('#true-value').val();

    let percentErr = (absError/trueVal)*100;
    let percentError = Math.abs(percentErr);
    let roundedPercentErr = Math.round(percentError, 2);

    if (percentError > 0) {
        $('#percent-error').html(roundedPercentErr + '% Error');
    } else {
        $('#percent-error').html('Error. Please check your input values and try again.');
    }
}

function calculateTripleM() {
    let mass = $('#mass').val();
    let molarMass = $('#mol-mass').val();
    let mol = $('#mol').val();
    let units = $('#units').val();

    for (let i = 0; i < 4; i++) {
        if (mass == '') {
            $('#mass').val(molarMass*mol);
        }
        if (mol == '') {
            let moles = Math.round((mass/molarMass), 3);
            $('#mol').val(moles);
        }
        if (units == '') {
            let unitNum = (mol*(6.02*(10**23)));
            let resultNum = parseFloat(unitNum.toPrecision(3));
            $('#units').val(resultNum);
        }
    }
}

function stretchTextBox() {
    let input = $('input');
    let span = $('span');
    input.addEventListener('input', function (event) {
        span.innerHTML = this.value.replace(/\s/g, '&nbsp;');
        this.style.width = span.offsetWidth + 'px';
    });
}
