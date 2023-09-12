# Concept

> definition : convertir de l'argent

## Properties
    Listes de teux de change

## Responsibilities
    Convertir un montant d'une devise en une autre devise
    Gérer des taux de change


## Invariants
    Pour convertir dans une autre devise il faut un taux de change
    Pas le droit d'ajouter des devises différents, il faut donc que la banque convertie
    Primitive opcetion : contraindre les données -> avoir les montants > 0

## Collaborators
    Monnaie : Tuple(montant, currency)