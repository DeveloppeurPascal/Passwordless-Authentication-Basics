# Passwordless Authentication Basics

[This page in english.](README.md)

Base de travail pour prendre en charge un espace membre sans mot de passe sur un site Internet.

L'authentification se fera à partir d'une adresse email (comme le font beaucoup (trop) de sites web).

En cas d'accès par email, une URL unique à durée limitée est envoyée par courriel. La visite de cette URL active la connexion (et l'inscription) à l'espace membre pour cette adresse email. L'adresse n'est valide qu'une seule fois.

**L'authentification sans mot de passe par email est clairement une technique à éviter car c'est la première chose que piratent les hackers. Même si techniquement ce dépôt est fonctionnel, je n'en recommande pas l'utilisation lorsque des données privées, personnelles ou sensibles sont diponibles post authentification.**

## Installation

Pour télécharger ce projet il est recommandé de passer par "git" mais vous pouvez aussi télécharger un ZIP directement depuis [son dépôt GitHub](https://github.com/DeveloppeurPascal/Passwordless-Authentication-Basics).

**Attention :** si le projet utilise des dépendances sous forme de sous modules ils seront absents du fichier ZIP. Vous devrez les télécharger à la main.

Un fichier SQL de base de données contient la description de la table "users" utilisée par les programmes PHP. Vous devez l'ajouter à votre base de données et configurer les paramètres du programmes présents dans le fichier ./src/protected/config-dist.inc.php en le copiant comme c'est expliqué dedans.

## Comment demander une nouvelle fonctionnalité, signaler un bogue ou une faille de sécurité ?

Si vous voulez une réponse du propriétaire de ce dépôt la meilleure façon de procéder pour demander une nouvelle fonctionnalité ou signaler une anomalie est d'aller sur [le dépôt de code sur GitHub](https://github.com/DeveloppeurPascal/Passwordless-Authentication-Basics) et [d'ouvrir un ticket](https://github.com/DeveloppeurPascal/Passwordless-Authentication-Basics/issues).

Si vous avez trouvé une faille de sécurité n'en parlez pas en public avant qu'un correctif n'ait été déployé ou soit disponible. [Contactez l'auteur du dépôt en privé](https://developpeur-pascal.fr/nous-contacter.php) pour expliquer votre trouvaille.

Vous pouvez aussi cloner ce dépôt de code et participer à ses évolutions en soumettant vos modifications si vous le désirez. Lisez les explications dans le fichier [CONTRIBUTING.md](CONTRIBUTING.md).

## Modèle de licence double

Ce projet est distribué sous licence [AGPL 3.0 ou ultérieure] (https://choosealicense.com/licenses/agpl-3.0/).

Si vous voulez l'utiliser en totalité ou en partie dans vos projets mais ne voulez pas en partager les sources ou ne voulez pas distribuer votre projet sous la même licence, vous pouvez acheter le droit de l'utiliser sous la licence [Apache License 2.0](https://choosealicense.com/licenses/apache-2.0/) ou une licence dédiée ([contactez l'auteur](https://developpeur-pascal.fr/nous-contacter.php) pour discuter de vos besoins).

## Supportez ce projet et son auteur

Si vous trouvez ce dépôt de code utile et voulez le montrer, merci de faire une donation [à son auteur](https://github.com/DeveloppeurPascal). Ca aidera à maintenir le projet (codes sources et binaires).

Vous pouvez utiliser l'un de ces services :

* [GitHub Sponsors](https://github.com/sponsors/DeveloppeurPascal)
* [Liberapay](https://liberapay.com/PatrickPremartin)
* [Patreon](https://www.patreon.com/patrickpremartin)
* [Paypal](https://www.paypal.com/paypalme/patrickpremartin)

ou si vous parlez français vous pouvez [vous abonner à Zone Abo](https://zone-abo.fr/nos-abonnements.php) sur une base mensuelle ou annuelle et avoir en plus accès à de nombreuses ressources en ligne (vidéos et articles).
