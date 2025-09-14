function kontrolloPorosine() {
    try {
        let name = document.getElementById("name").value;
        let email = document.getElementById("email").value;
        let address = document.getElementById("address").value;
        let product = document.getElementById("product").value;
        let payment = document.getElementById("payment-method").value;
        let isChecked = document.getElementById("accept-terms").checked;
        let quantity = document.getElementById("product-suggestion").value;


        validateName(name);
        validateEmail(email);
        validateAddress(address);
        validateQuantity(quantity);

        if (!product) throw "Please select a product!";
        if (!payment) throw "Please select a payment method!";
        if (!isChecked) throw "You must accept the terms and conditions!";

        let currentDate = new Date();
        let currentYear = currentDate.getFullYear();
        let dateString = `Order date: ${currentDate.toLocaleString()}`;
        console.log(dateString);

        let maxPayment = 999999.99;
        let minPayment = 0.01;
        console.log(`Maximum payment value: ${maxPayment.toExponential()}`);
        console.log(`Minimum payment value: ${minPayment.toString()}`);
        console.log(`Payment value: ${payment}`);
        console.log(`Payment value is NaN: ${isNaN(payment)}`);

       

        setTimeout(function() {
            alert("Order submitted successfully!");
        }, 3000);
    } catch (error) {
        alert(`Error: ${error}`);
    }
}
function validateQuantity(quantity) {
    if (!quantity || quantity <= 0) {
        throw "Please enter a valid quantity!";
    }
}
function validateName(name) {
    if (!name) throw "Name is required!";
}

function validateEmail(email) {
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailPattern.test(email)) throw "Email is not valid!";
}

function validateAddress(address) {
    if (!address) throw "Address is required!";
}

function Product(name, price) {
    this.name = name;
    this.price = price;
    this.displayInfo = function() {
        return `Product: ${this.name}, Price: ${this.price}`;
    };
}

let product1 = new Product("Americano", 10);
let product2 = new Product("Esspreso", 25);
let product3 = new Product("Makiato", 20);

console.log(product1.displayInfo());
console.log(product2.displayInfo());
console.log(product3.displayInfo());


let emailExample = "arila@example.com";
let emailMatch = emailExample.match(/^[^\s@]+@[^\s@]+\.[^\s@]+$/);
if (emailMatch) {
    console.log("Email is valid");
} else {
    console.log("Email is invalid");
}

let updatedEmail = emailExample.replace("example.com", "gmail.com");
console.log(`Updated email: ${updatedEmail}`);