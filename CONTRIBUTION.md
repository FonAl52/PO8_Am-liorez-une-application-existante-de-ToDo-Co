# Documentation Contribution

## Introduction

Cette documentation décrit le système de contribution utilisé dans notre application **ToDo & Co**. 

## Table des Matières

1. [**Présentation du Projet**](https://www.notion.so/Documentation-Contribution-74e4dd8297b148acbb7854a14fc455be?pvs=21)
2. [**Prérequis**](https://www.notion.so/Documentation-Contribution-74e4dd8297b148acbb7854a14fc455be?pvs=21)
3. [**Règles Générales**](https://www.notion.so/Documentation-Contribution-74e4dd8297b148acbb7854a14fc455be?pvs=21)
4. [**Processus de Contribution**](https://www.notion.so/Documentation-Contribution-74e4dd8297b148acbb7854a14fc455be?pvs=21)
5. [**Types de Contributions Acceptées**](https://www.notion.so/Documentation-Contribution-74e4dd8297b148acbb7854a14fc455be?pvs=21)
6. [**Revue de Code et Validation**](https://www.notion.so/Documentation-Contribution-74e4dd8297b148acbb7854a14fc455be?pvs=21)
7. [**Communiquer avec l'Équipe**](https://www.notion.so/Documentation-Contribution-74e4dd8297b148acbb7854a14fc455be?pvs=21)

1. **Présentation du Projet**
    - **ToDo & Co** est un projet de todo list récupérer sur une version 3.4 de symfony il à été update vers la version 6.4 du framework, et de nouvelles fonctionnalités ont été ajouter (cf. le document d’Audit qualité et performance pour en savoir plus).
    - Maintenir le projet à jour, améliorer la qualité de code du projet et ajouter de nouvelles fonctionnalités.
2. **Prérequis**
    - Pour contribuer efficacement à un projet Symfony, il est essentiel d'avoir certains outils et configurations de base. Voici une liste non exostive des éléments nécessaires :
    
    **Outils de Version Control : Git**
    
    1. **Git**
    2. **GitHub ou GitLab**
    
    **Éditeurs de Code et IDEs**
    
    1. **Visual Studio Code (VS Code)**
    2. **PhpStorm**
    
    **Environnement de Développement Symfony**
    
    1. **Composer**
    2. **Symfony CLI**
    
    Bases de Données
    
    1. **PostgreSQL**
    2. MySQL **(ou autre système de gestion de base de données)**
    
    **Outils supplémentaires**
    
    1. **Node.js et NPM/Yarn**
    2. **Webpack Encore**
3. **Règles Générales**
    - Code de conduite des contributeurs.
        
        En tant que contributeur, vous acceptez de respecter les autres membres de la communauté, de faire preuve de courtoisie et de professionnalisme, et d'utiliser un langage et des actions inclusifs et respectueux. Nous nous engageons à offrir un environnement exempt de harcèlement pour tous. Tout comportement jugé inapproprié pourra entraîner des sanctions.
        
    - Standards de codage et conventions à respecter.
        
        Dans le cadre du développement avec Symfony, il est essentiel de suivre des standards de codage clairs et cohérents pour garantir la lisibilité et la maintenabilité du code. Voici quelques principes clés :
        
        - **PSR-1 et PSR-2** : Utilisez les standards PSR-1 et PSR-2 pour assurer la conformité du code.
        - **Nommage** : Utilisez des noms de classes, méthodes et variables explicites et significatifs. Respectez les conventions de nommage camelCase pour les méthodes et variables, et PascalCase pour les classes.
        - **Indentation et Espacement** : Utilisez des indentations de 4 espaces et évitez les tabulations.
        - **Structure des fichiers** : Organisez les fichiers de manière logique en suivant la structure des répertoires recommandée par Symfony.
        - **Commentaires** : Ajoutez des commentaires pertinents pour clarifier le code complexe ou non intuitif.
    - Documentation et commentaires du code.
        
        Une documentation et des commentaires appropriés sont essentiels pour maintenir et comprendre le code sur le long terme. Voici quelques directives :
        
        - **PHPDoc** : Utilisez PHPDoc pour documenter les classes, méthodes et propriétés. Incluez des descriptions claires et concises, ainsi que les types de paramètres et de retour.
        - **Commentaires en ligne** : Ajoutez des commentaires en ligne pour expliquer des sections de code complexes ou des choix de conception spécifiques.
        - **Fichiers README** : Chaque module ou composant doit avoir un fichier README détaillant son utilisation, ses dépendances et ses configurations spécifiques.
    - Tests unitaires et intégration continue.
        
        Les tests unitaires et l'intégration continue (CI) sont essentiels pour garantir la stabilité et la fiabilité du code. Voici quelques pratiques à suivre :
        
        - **Tests Unitaires** : Écrivez des tests unitaires pour toutes les nouvelles fonctionnalités et pour les bugs corrigés. Utilisez PHPUnit, le framework de tests recommandé pour Symfony.
        - **Couverture de Test** : Assurez-vous que le code nouvellement ajouté est bien couvert par les tests et que la couverture de test globale ne diminue pas.
        - **Intégration Continue** : Configurez des outils d'intégration continue (CI) tels que GitHub Actions, GitLab CI, ou Jenkins pour automatiser l'exécution des tests à chaque commit.
        - **Analyse Statique** : Utilisez des outils d'analyse statique comme PHPStan ou Psalm pour détecter les erreurs potentielles et améliorer la qualité du code.
        - **Revue de Code** : Soumettez vos pull requests pour une revue de code afin de garantir la qualité et la conformité du code aux standards du projet.
4. **Processus de Contribution**
    - Cloner le dépôt est la première étape pour commencer à contribuer au projet.
        
        Voici comment procéder :
        
        **Cloner le dépôt** : Ouvrez un terminal et exécutez la commande suivante :
        
        ```bash
        https://github.com/FonAl52/PO8_Am-liorez-une-application-existante-de-ToDo-Co.git
        ```
        
        Cette commande créera une copie locale du dépôt sur votre machine.
        
    - Création et gestion des branches.
        
        La gestion efficace des branches est essentielle pour le développement collaboratif. Voici les étapes à suivre :
        
        1. **Créer une nouvelle branche** : Avant de commencer à travailler sur une nouvelle fonctionnalité ou un bug, créez une nouvelle branche à partir de la branche `main`: Utilisez un nom de branche descriptif qui reflète le travail effectué, par exemple `feature/ajouter-authentification` ou `bugfix/corriger-erreur-login`.
            
            ```bash
            git checkout -b nom-de-la-branche
            ```
            
        2. **Changer de branche** : Pour basculer entre les branches, utilisez :
            
            ```bash
            git checkout nom-de-la-branche
            ```
            
        3. **Gérer les branches** : Une fois votre travail terminé et fusionné, vous pouvez supprimer les branches locales et distantes pour garder le dépôt propre :
            
            ```bash
            git branch -d nom-de-la-branche
            git push origin --delete nom-de-la-branche
            ```
            
    - Écriture de messages de commit clairs et concis.
        
        Les messages de commit bien rédigés facilitent la compréhension de l'historique des modifications. Voici quelques conseils :
        
        1. **Message de commit court et descriptif.**
        2. **Messages fréquents** : Commitez souvent pour enregistrer les étapes significatives de votre développement, mais assurez-vous que chaque commit soit un changement logique et complet.
    - Processus de création de pull requests (PR).
        
        Les pull requests (PR) sont essentielles pour la revue de code et l'intégration des modifications. Voici comment créer une PR efficace :
        
        1. **Pousser les modifications vers GitHub** : Une fois votre travail terminé, poussez votre branche vers le dépôt distant :
            
            ```bash
            git push origin nom-de-la-branche
            ```
            
        2. **Créer la PR** : Allez sur la page GitHub du projet et cliquez sur "New Pull Request". Sélectionnez votre branche et la branche dans laquelle vous souhaitez fusionner.
        3. **Décrire la PR** : Fournissez une description détaillée de votre PR. Expliquez les changements apportés, pourquoi ils sont nécessaires et tout autre détail pertinent. Utilisez des listes à puces et des sections pour structurer votre description.
        4. **Attendre la revue** : Demandez une revue de code en assignant des reviewers. Répondez aux commentaires et apportez les modifications nécessaires. Une fois approuvée, la PR sera fusionnée par un mainteneur du projet.
        
        En suivant ces pratiques, vous garantirez une collaboration fluide et efficace avec les autres contributeurs du projet Symfony.
        
5. **Types de Contributions Acceptées**
    - Corrections de bugs.
    - Ajout de nouvelles fonctionnalités.
    - Amélioration de la documentation.
    - Tests et qualité du code.
6. **Revue de Code et Validation**
    - Processus de revue de code.
        1. **Création de la Pull Request (PR)** : Une fois que vous avez terminé vos modifications, créez une PR depuis votre branche de travail vers la branche principale (`main`).
        2. **Assignation des reviewers** : Assignez la PR à des reviewers, généralement des mainteneurs du projet ou des membres de l'équipe expérimentés. Vous pouvez également mentionner des reviewers potentiels dans la description de la PR.
        3. **Revue initiale** : Les reviewers examinent le code pour s'assurer qu'il suit les standards de codage, qu'il est bien documenté et qu'il respecte les conventions du projet. Ils vérifient également que les tests passent et que le code fonctionne comme prévu.
        4. **Commentaires et feedback** : Les reviewers laissent des commentaires sur la PR pour signaler des améliorations possibles, des erreurs ou des questions. Le contributeur doit répondre à ces commentaires, effectuer les modifications nécessaires et pousser les changements mis à jour.
        5. **Revue finale et approbation** : Une fois que toutes les modifications demandées ont été apportées, les reviewers effectuent une dernière vérification. Si tout est correct, ils approuvent la PR.
        6. **Fusion de la PR** : Une fois approuvée, la PR est fusionnée dans la branche principale par un mainteneur.
    - Critères de validation des PR.
        
        Pour qu'une PR soit validée, elle doit respecter certains critères de qualité :
        
        1. **Respect des standards de codage** : Le code doit suivre les standards de codage et les conventions établies plus haut ici.
        2. **Documentation et commentaires** : Le code doit être bien documenté, avec des commentaires clairs et pertinents. Toute nouvelle fonctionnalité ou modification doit être expliquée dans la documentation du projet.
        3. **Tests réussis** : Toutes les modifications doivent être accompagnées de tests unitaires et/ou fonctionnels. Les tests existants doivent passer sans erreur. Si nécessaire, des tests supplémentaires doivent être ajoutés pour couvrir les nouvelles fonctionnalités ou corrections.
        4. **Cohérence et clarté** : Le code doit être logique, cohérent et facile à lire. Les modifications doivent être isolées et bien délimitées pour faciliter la revue.
        5. **Respect des bonnes pratiques de sécurité** : Le code doit respecter les bonnes pratiques de sécurité pour éviter les vulnérabilités potentielles.
    - Délais estimés pour la revue et la validation.
        
        Le temps nécessaire pour la revue et la validation des PR peut varier en fonction de plusieurs facteurs, notamment la complexité des modifications et la disponibilité des reviewers. 
        
        En moyenne il faudra de 4 à 10 jours ouvrables pour qu’une PR soit fusionnée.
        
7. **Communiquer avec l'Équipe**
    - Utilisation des issues pour discuter des problèmes et des suggestions.
    - Canaux de communication disponibles Github, Discord.
    - Participation aux réunions de l'équipe : Skype, Google meet (si nécessaire).

### Feedback et Améliorations

Les contributeurs sont encouragés à fournir des retours sur la documentation de contribution afin de l'améliorer en continu. Toute suggestion peut être soumise via une issue ou une pull request.