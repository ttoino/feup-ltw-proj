<?php 
    declare(strict_types=1); 

    require_once(dirname(__DIR__)."/database/models/restaurant.php");
    require_once(dirname(__DIR__)."/database/models/dish.php");
    require_once(dirname(__DIR__)."/database/models/menu.php");
    require_once(dirname(__DIR__)."/database/models/user.php");
    require_once(dirname(__DIR__)."/database/models/review.php");
    
    require_once(dirname(__DIR__)."/lib/session.php");
?>

<?php function createRestaurantCard(Restaurant $restaurant, int $h) { ?>
    <article
        class="card responsive interactive"
        data-card-type="restaurant"
        data-restaurant-id="<?= $restaurant->id ?>"
    >
        <div class="full media gradient thumbnail">
            <img
                src="<?= $restaurant->getImagePath() ?>"
                width="1920"
                height="1080"
                alt="Profile picture for <?= $restaurant->name ?>"
            />
        </div>
        <header class="header">
            <h<?= $h ?> class="title h6">
                <a
                    href="/restaurant/?id=<?= $restaurant->id ?>"
                    class="card-link"
                >
                    <?= $restaurant->name ?>
                </a>
            </h<?= $h ?>>
            <span class="subtitle subtitle2 secondary"><?= $restaurant->address ?></span>
            <?php if ($restaurant->score !== null) { ?>
            <span class="chip right"><?php createIcon(icon: "star") ?><?= round($restaurant->score, 1) ?></span>
            <?php } ?>
        </header>
        <?php
        if (($categories = $restaurant->getCategories()) !== []) {
            createCategoryList($categories);
        }

        $session = new Session();

        if ($session->isAuthenticated()) {
            if ($restaurant->owner === $session->get('user')) {
                createButton(
                    type: ButtonType::ICON,
                    text: "Edit",
                    icon: "edit",
                    class: "top-right",
                    href: "/restaurant/edit.php?id=$restaurant->id");
            } else {
                $currentUser = User::getById($session->get('user'));

                if ($currentUser !== null && $restaurant->isLikedBy($currentUser)) {
                    $state = "on";
                    $text = "Unfavorite";
                } else {
                    $state = "off";
                    $text = "Favorite";
                }
    
                createButton(
                    type: ButtonType::ICON, text: $text, class: "top-right toggle",
                    attributes: 
                        "data-on-icon=\"favorite\"\n".
                        "data-off-icon=\"favorite_border\"\n".
                        "data-toggle-state=\"$state\"\n".
                        "data-favorite-button"
                );
            }
        } ?>
    </article>
<?php } ?>

<?php function createDishCard(Dish $dish, int $h, bool $show_restaurant = false, bool $edit = false) { 
    if ($show_restaurant && ($restaurant = $dish->getRestaurant()) === null) return;

    if ($edit) { ?>
        <article
            class="card responsive"
            data-card-type="edit-dish"
            data-dish-id="<?= $dish->id ?>"
        >
            <label class="image-input full media square gradient">
                <img
                    src="<?= $dish->getImagePath() ?>"
                    alt=""
                >
                <input
                    class="visually-hidden"
                    type="file"
                    name="dishes_to_edit[<?= $dish->id ?>]"
                    accept="image/*"
                >
            </label>

            <?php
            createTextField(
                name: "dishes_to_edit[$dish->id][name]",
                label: 'Name',
                value: $dish->name
            );
            createTextField(
                name: "dishes_to_edit[$dish->id][price]",
                label: 'Price',
                value: sprintf('%.2f', $dish->price),
                type: 'number',
                min: 0,
                step: .01
            );

            createCategoryList($dish->getCategories(), $edit, "dish-$dish->id-categories");
            createCategoriesDialog($dish, "dishes_to_edit[$dish->id][categories][]", "dish-$dish->id-categories");
    
            createButton(
                type: ButtonType::ICON,
                text: 'Delete',
                icon: 'delete',
                class: "top-right",
                attributes: "data-delete-button"
            );
            ?>

            <input
                type="hidden"
                disabled
                name="dishes_to_delete[]"
                value="<?= $dish->id ?>"
            >
        </article>
    <?php } else { ?>
        <article
            class="card responsive interactive"
            data-card-type="dish"
            data-dish-id="<?= $dish->id ?>"
        >
            <div class="full media gradient square">
                <img
                    src="<?= $dish->getImagePath() ?>"
                    width="512"
                    height="512"
                    alt="Dish picture for <?= $dish->name ?>"
                />
            </div>
            <header class="header">
                <h<?= $h ?> class="title h6">
                    <a href="#" class="card-link"><?= $dish->name ?></a>
                </h<?= $h ?>>
                <span class="subtitle subtitle2 secondary">
                    <?= sprintf('%.2f€', $dish->price) ?>
                    <?php if ($show_restaurant) echo "· $restaurant->name" ?>
                </span>
            </header>
            <?php
            createCategoryList($dish->getCategories(), $edit);

            $session = new Session();

            if ($session->isAuthenticated()) {

                $currentUser = User::getById($session->get('user'));

                if ($currentUser !== null && $dish->isLikedBy($currentUser)) {
                    $state = "on";
                    $text = "Unfavorite";
                } else {
                    $state = "off";
                    $text = "Favorite";
                }

                createButton(
                    type: ButtonType::ICON, text: $text, class: "top-right toggle",
                    attributes: 
                        "data-on-icon=\"favorite\"\n".
                        "data-off-icon=\"favorite_border\"\n".
                        "data-toggle-state=\"$state\"\n".
                        "data-dish-id=\"$dish->id\"\n".
                        "data-favorite-button"
                );
            } ?>
        </article>
<?php } } ?>

<?php function createMenuCard(Menu $menu, int $h, bool $show_restaurant = false, bool $edit = false) { 
    if ($show_restaurant && ($restaurant = $menu->getRestaurant()) === null) return;

    if ($edit) { ?>
        <article
            class="card responsive"
            data-card-type="edit-menu"
            data-menu-id="<?= $menu->id ?>"
        >
            <label class="image-input full media square gradient">
                <img
                    src="<?= $menu->getImagePath() ?>"
                    alt=""
                >
                <input
                    class="visually-hidden"
                    type="file"
                    name="menus_to_edit[<?= $menu->id ?>]"
                    accept="image/*"
                >
            </label>

            <?php
            createTextField(
                name: "menus_to_edit[$menu->id][name]",
                label: 'Name',
                value: $menu->name
            );
            createTextField(
                name: "menus_to_edit[$menu->id][price]",
                label: 'Price',
                value: sprintf('%.2f', $menu->price),
                type: 'number',
                min: 0,
                step: .01
            );
    
            createButton(
                type: ButtonType::ICON,
                text: 'Delete',
                icon: 'delete',
                class: "top-right",
                attributes: "data-delete-button"
            );

            createMenuDishList($menu->getDishes(), true, "menu-$menu->id-dishes");
            createDishesDialog($menu, "menus_to_edit[$menu->id][dishes][]", "menu-$menu->id-dishes");
            ?>

            <input
                type="hidden"
                disabled
                name="menus_to_delete[]"
                value="<?= $menu->id ?>"
            >
        </article>
    <?php } else { ?>
    <article 
        class="card responsive interactive"
        data-card-type="menu" 
        data-menu-id="<?= $menu->id ?>"
    >
        <div class="full media square">
            <img
                src="<?= $menu->getImagePath() ?>"
                width="512"
                height="512"
                alt="Menu picture for <?= $menu->name ?>"
            />
        </div>
        <header class="header">
            <h<?= $h ?> class="title h6">
                <a href="#" class="card-link"><?= $menu->name ?></a>
            </h<?= $h ?>>
            <span class="subtitle subtitle2 secondary">
                <?= sprintf('%.2f€', $menu->price) ?>
                <?php if ($show_restaurant) echo "· $restaurant->name" ?>
            </span>
        </header>

        <?php createMenuDishList($menu->getDishes()); ?>
    </article>
<?php } } ?>

<?php function createProfileCard(User $user, int $h) { ?>
    <article 
        class="card responsive interactive"
        data-card-type="user" 
        data-user-id="<?= $user->id ?>"
    >
        <header class="header">
            <img
                src="<?= $user->getImagePath() ?>"
                width="512"
                height="512"
                alt="Profile picture for <?= $user->name ?>"
                class="avatar medium"
            />
            <h<?= $h ?> class="title h6">
                <a 
                    href="/profile/?id=<?= $user->id ?>"
                    class="card-link"
                >
                    <?= $user->name ?>
                </a>
            </h<?= $h ?>>
            <span class="subtitle subtitle2 secondary">
                <?= $user->address ?>
            </span>
        </header>
    </article>
<?php } ?>

<?php function showReview(Review $review) { 
    $user = $review->getUser();
    $response = $review->getResponse();

    if ($user == null) return;
    ?>
    <article class="review" data-review-id="<?= $review->id ?>">
        <a href="/profile/?id=<?= $user->id ?>">
            <header class="header">
                <img 
                    src=<?= $user->getImagePath() ?>
                    alt="Review profile image for <?=$user->name?>"
                    class="avatar small"
                >
                <span class="title"><?= $user->name ?></span>
                <span class="subtitle secondary"><?= date_create($review->review_date)->format('j/n/Y') ?></span>
                <span class="chip right"><?php createIcon("star"); ?><?= round($review->score, 1) ?></span>
            </header>
        </a>
        <p class="review-content"><?= $review->text ?></p>

        <?php if ($response) { 
            $replier = $review->getRestaurant()->getOwner();
        ?>
        <article class="review" data-response-id="<?= $response->id ?>">
            <a href="/profile/?id=<?= $replier->id ?>">
                <header class="header">
                    <img 
                        src=<?= $replier->getImagePath() ?>
                        alt="Review profile image for <?=$replier->name?>"
                        class="avatar small"
                    >
                    <span class="title"><?= $replier->name ?></span>
                    <span class="subtitle secondary"><?= date_create($response->response_date)->format('j/n/Y') ?></span>
                </header>
            </a>
            <p class="review-content"><?= $response->text ?></p>
        </article>
        <?php } else if ($review->getRestaurant()->owner === (new Session())->get('user')) {
            createButton(ButtonType::TEXT, 'Reply', attributes: "data-reply-button");
        } ?>
    </article>
<?php } ?>

<?php function createCartDishCard(Dish $dish, int $amount) { ?>
    <article class="card responsive" data-cart-card-type="dish" data-cart-card-id="<?= $dish->id ?>">
        <header class="header">
            <img 
                src="<?= $dish->getImagePath() ?>" 
                alt="Image for dish <?= $dish->id ?> in cart page"
                width="512"
                height="512"
                class="avatar medium"
            >
            <h3 class="title h6">
                <span class="product-amount"><?= $amount ?></span>&times; <?= $dish->name ?>
            </h3>
            <span class="subtitle subtitle2 secondary">
                <?= sprintf('%.2f€', $dish->price) ?>
            </span>
            <div class="right">
                <?php
                    createButton(
                        type: ButtonType::ICON, 
                        icon: "add", 
                        text: "Add one $dish->name to the cart", 
                        attributes: "data-add-unit"
                    );
                    createButton(
                        type: ButtonType::ICON, 
                        icon: "remove", 
                        text: "Remove one $dish->name from the cart", 
                        attributes: "data-remove-unit"
                    );
                    createButton(
                        type: ButtonType::ICON, 
                        icon: "delete", 
                        text: "Remove $dish->name from the cart",
                        attributes: "data-delete-unit"
                    );
                    ?>
            </div>
        </header>
        <input type="hidden" name="dishes_to_order[<?= $dish->id ?>]" value=<?= $amount ?>>
    </article>
<?php } ?>

<?php function createCartMenuCard(Menu $menu, int $amount) { ?>
    <article class="card responsive" data-cart-card-type="menu" data-cart-card-id="<?= $menu->id ?>">
        <header class="header">
            <img 
                src="<?= $menu->getImagePath() ?>" 
                alt="Image for menu <?= $menu->id ?> in cart page"
                width="512"
                height="512"
                class="avatar medium"
            >
            <h3 class="title h6">
                <span class="product-amount"><?= $amount ?></span>&times; <?= $menu->name ?>
            </h3>
            <span class="subtitle subtitle2 secondary">
                <?= sprintf('%.2f€', $menu->price) ?>
            </span>
            <div class="right">
                <?php   
                    createButton(
                        type: ButtonType::ICON,
                        icon: "add",
                        text: "Add one $menu->name to the cart", 
                        attributes: "data-add-unit"
                    );
                    createButton(
                        type: ButtonType::ICON,
                        icon: "remove",
                        text: "Remove one $menu->name from the cart", 
                        attributes: "data-remove-unit"
                    );
                    createButton(
                        type: ButtonType::ICON,
                        icon: "delete",
                        text: "Remove $menu->name from the cart",
                        attributes: "data-delete-unit"
                    );
                    ?>
            </div>
        </header>
        <input type="hidden" name="menus_to_order[<?= $menu->id ?>]" value=<?= $amount ?>>
    </article>
<?php } ?>

<?php function showOrder(Order $order, bool $show_restaurant) { 
    $user = $order->getUser();
    $restaurant = $order->getRestaurant();

    if ($user == null) return;

    $total = 0;
    ?>
    <article class="order card responsive" data-order-id="<?= $order->id ?>">
        <?php if ($show_restaurant) { ?>
        <a href="/restaurant/?id=<?= $restaurant->id ?>">
            <header class="header">
                <img 
                    src=<?= $restaurant->getImagePath() ?>
                    alt="Order restaurant image for <?= $restaurant->name ?>"
                    class="avatar small"
                >
                <span class="title"><?= $restaurant->name ?></span>
                <span class="subtitle secondary"><?= date_create($order->order_date)->format('j/n/Y') ?></span>
                <span class="chip right order-state"><?= $order->getStateString() ?></span>
            </header>
        </a>
        <?php } else { ?>
        <a href="/profile/?id=<?= $user->id ?>">
            <header class="header">
                <img 
                    src=<?= $user->getImagePath() ?>
                    alt="Order profile image for <?= $user->name ?>"
                    class="avatar small"
                >
                <span class="title"><?= $user->name ?></span>
                <span class="subtitle secondary"><?= date_create($order->order_date)->format('j/n/Y') ?></span>
                <span class="chip right state"><?= $order->getStateString() ?></span>
            </header>
        </a>
        <?php } ?>
        <ul>
            <?php foreach ($order->getDishes() as list($dish, $amount)) { 
                $total += $dish->price * $amount;
            ?>
            <li><?= $amount ?>&times; <?= $dish->name ?></li>
            <?php } ?>
            <?php foreach ($order->getMenus() as list($menu, $amount)) { 
                $total += $menu->price * $amount;
            ?>
            <li><?= $amount ?>&times; <?= $menu->name ?></li>
            <?php } ?>
        </ul>
        <p class="total">Total · <?= $total ?>€</p>
        <?php 
        if (!$show_restaurant) {
            if ($order->state === 'pending') {
                createButton(
                    ButtonType::OUTLINED,
                    'Cancel',
                    attributes: "data-order-button=\"canceled\""
                );
                createButton(
                    ButtonType::CONTAINED,
                    'Accept',
                    attributes: "data-order-button=\"in_progress\""
                );
            } else if ($order->state === 'in_progress') {
                createButton(
                    ButtonType::CONTAINED,
                    'Mark as ready',
                    attributes: "data-order-button=\"ready\""
                );
            } else if ($order->state === 'ready') {
                createButton(
                    ButtonType::CONTAINED,
                    'Mark as delivered',
                    attributes: "data-order-button=\"delivered\""
                );
            }
        }
        ?>
    </article>
<?php } ?>
