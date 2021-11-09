/**
 * VALIDATE LENGTH INPUT
 * if input string length is between 3 and 200
 * if is some text
 * and render alert -> flash-message
 *
 * @param {string} text
 * @returns {boolean} && alert -> flash-message
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
 * @param {string} previousText
 * @returns {boolean}
 */
export function validateMatchInputs(currentText, previousText) {
  if(currentText === previousText) return false
  else return true
}