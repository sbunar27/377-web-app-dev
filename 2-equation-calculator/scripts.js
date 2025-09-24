function calculatePercentComposition() {
    let elementMass = $('#element-mass').val()
    let compoundMass = $('#compound-mass').val()
    let elementAmount = $('#element-amount').val()
    let elementName = $('#element-name').val()
    
    let percentComposition = ((elementMass*elementAmount)/compoundMass)*100
    let roundedComposition = Math.round(percentComposition*100) / 100
    $('#percent-composition').html(roundedComposition + '% ' + elementName)
}

function calculateAbsError() {
    // do later
}