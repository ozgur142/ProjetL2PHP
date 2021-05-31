
function minDate() {

    var todaysDate = new Date();

    var year = todaysDate.getFullYear();                        // YYYY

    var month = ("0" + (todaysDate.getMonth() + 1)).slice(-2);  // MM

    var day = ("0" + todaysDate.getDate()).slice(-2);           // DD

    var minDate = (year +"-"+ month +"-"+ day); 

    document.getElementById('dateDeb').min = minDate;
}

