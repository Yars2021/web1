function setX(x) {
    document.getElementsByName("x")[0].value = x;
    document.getElementById("labelX").innerText = x;
}

function setY() {
    document.getElementById("labelY").innerText = document.getElementsByName("y")[0].value;
}

function setR(r) {
    document.getElementsByName("r")[0].value = r;
    document.getElementById("labelR").innerText = r;
}

function validateY() {
    let y = document.getElementsByName("y")[0].value;
    // Validate pattern matching: '^\-?\d+$' and remove all non-matching characters
    if (!y.match(/^[+-]?([0-9]+([.][0-9]*)?|[.][0-9]+)$/)) {
        y = '';
    }
    // Check range (-3..5)
    if ((y !== '') &&
        ((y < -3) || (y > 5))) {
        y = '';
    }
    document.getElementsByName("y")[0].value = y;
    setY();
}
