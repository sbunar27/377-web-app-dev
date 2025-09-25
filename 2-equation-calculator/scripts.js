function calculateAbsError() {
    let measured = $('#measured-value').val()
    let theoretical = $('#theoretical-value').val()
    let errorNum = measured - theoretical

    let absoluteError = Math.abs(errorNum)
    let roundedAbsError = Math.round(absoluteError*1000) /1000

    $('#absolute-error').html(roundedAbsError)
}

function calculatePercentComposition() {
    let elementMass = $('#element-mass').val()
    let compoundMass = $('#compound-mass').val()
    let elementAmount = $('#element-amount').val()
    let elementName = $('#element-name').val()
    
    let percentComposition = ((elementMass*elementAmount)/compoundMass)*100
    let roundedComposition = Math.round(percentComposition*100) / 100

    if (percentComposition > 0 && percentComposition <= 100) {
        $('#percent-composition').html(roundedComposition + '% ' + elementName)
    } else {
        $('#percent-composition').html('Error. Please check your input values and try again.')
    }
}

function calculatePercentError() {
    let absError = $('#abs-error').val()
    let trueVal = $('#true-value').val()

    let percentErr = (absError/trueVal)*100
    let percentError = Math.abs(percentErr)
    let roundedPercentErr = Math.round(percentError*100) / 100

    if (percentError > 0) {
        $('#percent-error').html(roundedPercentErr + '% Error')
    } else {
        $('#percent-error').html('Error. Please check your input values and try again.')
    }
}

function calculateTripleM() {
    let mass = $('#mass').val()
    let molarMass = $('#mol-mass').val()
    let mol = $('#mol').val()
    let units = $('#units').val()

    // mass = parseInt(mass)
    // molarMass = parseInt(molarMass)
    // mol = parseInt(mol)
    // units = parseInt(units)

    for (let i = 0; i < 4; i++) {
        if (mass == '') {
            $('#mass').val(molarMass*mol)
        }
        if (mol == '') {
            let moles = Math.round((mass/molarMass)*1000)/1000
            $('#mol').val(moles)
        }
        // still doesnt work :(
        if (units == '') {
            let unitNum = (mol*(6.02(10**23)))
            $('#units').val(unitNum)
        }
    }
}

// function convertValues() {
//     // CONVERSION FACTORS AND PREFIXES (RESPECTFUL)
//     let prefixes = ['giga', 'mega', 'kilo', 'centi', 'milli', 'micro', 'nano', 'pico']
//     let conversions = [10**9, 10**6, 10**3, 10**(-2), 10**(-3), 10**(-6), 10**(-9), 10**(-12)]
    
//     let num = $('#num').val()
//     let prefix = $('#prefix').val().toLowerCase()
//     for (let i = 0; i < prefixes.length; i++) {
//             if (prefix == prefixes[i]) {
//                 let number = parseInt(num)*parseInt(conversions[i])
//                 break
//             }
//     }
//     $('#converted-values').html(number + ' ' + prefix + 'grams')
// }
