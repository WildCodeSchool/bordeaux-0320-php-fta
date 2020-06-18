function copyToClipboard() {
    /* Get the text field */
    const copyText = document.getElementById('phoneNumber');

    /* Select the text field */
    copyText.select();
    copyText.setSelectionRange(0, 99999); /* For mobile devices */

    /* Copy the text inside the text field */
    document.execCommand('copy');
}
