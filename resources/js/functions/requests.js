/**
 * VALIDATE LENGTH INPUT
 * if input string length chain is between 3 and 200
 * and render alert -> flash-message
 *
 * @param {string} text
 * @returns alert -> flash-message
 */
export function validateLengthInputs(text) {
  if(!text) return false

  else if(text.length <= 3) {
    window.mitter.emit('show-alert', {
      message: 'Input is too short',
      status: false
    })
    return false
  }
  
  else if(text.length >= 200) {
    window.mitter.emit('show-alert', {
      message: 'Input is too long',
      status: false
    })
    return false
  }

  else return true
}


/**
 * VALIDATE MATCH INPUTS
 * If currentText is same with previousText then return false.
 * If not return true.
 *
 * @param {string} currentText
 * @returns {boolean}
 */
export function validateMatchInputs(currentText, previousText) {
  if(currentText === previousText) return false
  else return true
}