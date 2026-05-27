<script>
        console.log("Pagina principala functioneaza!");
    </script>

<script>
let pare = 0;
let impare = 0;

// Introducere numere
let input = prompt("Introdu 10 numere separate prin spațiu:");

// Transformăm în array
let numbers = input.split(" ");

console.log("Rezultate:");

// Verificare
for (let i = 0; i < numbers.length; i++) {

    if (numbers[i] % 2 == 0) {
        console.log(numbers[i] + " este PAR");
        pare++;
    } else {
        console.log(numbers[i] + " este IMPAR");
        impare++;
    }
}

console.log("Numere pare: " + pare);
console.log("Numere impare: " + impare);

</script>