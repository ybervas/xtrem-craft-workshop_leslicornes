# Backlog

## What can be improved in the codebase ?

Bank.php :

- Noms des paramètres pas explicites
- L’attribut exchangeRates est non typé
- La fonction create manque un type de retour
- La fonction convert =>$this->exchangeRates[($currency1 . '->' . $currency2)]; peut être refactor
- Lignes trop grandes
- Probleme de logique dans la fonction convert !($currency1 == $currency2)

Currency.php :

- Unused
- MissingExchangeRateException.php :




MoneyCalculator.php :

- Manque la documentation entière /
- Paramètres non utilisés dans chaque méthodes
- Nommage ambigu des variables
- Méthodes pas claires

BankTest.php :

- Couverture des tests pas suffisantes
- Lignes trop grandes
- Refactor bank qui peut être une instance unique (singleton ou fabrique)

>

- Conventions de nommage non respectées
- Indentation
- Magic string
- Trop de détails
- Doc ?
- Invariants non explicites

