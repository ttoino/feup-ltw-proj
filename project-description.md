# LTW Project

Project description for the 2022 edition of the Web Languages and Technologies course.

## Objective

Create a website where **restaurants** can list and offer their **menus** for **take-away**. To create this application, students should:

- Create an SQLite **database** that stores information about restaurants, menus, dishes, customers, and orders.
- Create documents using **HTML** and **CSS** representing the application's web pages.
- Use **PHP** to generate those web pages after retrieving/changing data from the database.
- Use **Javascript** to enhance the user experience (for example, using Ajax).

## Requirements

Requirements are sparsely defined so that each group has some degree of freedom.

The minimum expected set of requirements is the following:

- **All users** should be able to (users can simultaneously be customers and restaurant owners):
  - **Register** a new account.
  - **Login** and **Logout**.
  - Edit their **profile** (at least username, password, address, and phone number).

- **Restaurant owners** should be able to:
  - Add and edit **information** about each one of their **restaurants**. Including the **name**, **address**, and **category**.
  - Add a list of **dishes**, their **names**, **prices**, **photos** and **categories**.
  - List and respond to **reviews** made by customers.
  - List **orders** and change their **state** (e.g., received, preparing, ready, delivered).

- **Customers** should be able to:
  - **Search restaurants** by name, dish name, average review score, etc.
  - **Order** dishes from a single restaurant.
  - Mark restaurants and dishes as **favorites**.
  - **Review** a restaurant they have ordered from (at least score and some text).

Students should also make sure that:

- The following **technologies** are all used:\
  HTML, CSS, PHP, Javascript, Ajax/JSON, PDO/SQL (using sqlite).

- The website should be as **secure** as possible.\
  Have special attention to SQL injection, XSS and CSRF attack protection, and good password storage principles.

- Code should be **organized** and **consistent**.

- Design doesn't need to be *top-notch* but should be **clean** and **consistent** throughout the site. It should also work on **mobile** devices.

- Frameworks are **not allowed**.

- Small helper libraries (*e.g.*, displaying a gallery of pictures) **might be** allowed (talk with your practical class teacher).

Some suggested **additional** requirements. These requirements are a way of making sure each project is unique. You **do not have** to implement all of these:

- Restaurant owners should be able to add **temporary promotions** to dishes.
- There should be a way to keep each dish's **price history**.
- Users should be able to **send photos** of the dishes they ordered so that other users know what to expect.
- Users should receive a system **notification** every time something significant happens (e.g., new order, the ordered dish is on its way).
- Add more information about restaurants and dishes.
- Use the [geolocation API](https://developer.mozilla.org/en-US/docs/Web/API/Geolocation_API) and restaurant addresses to order the restaurants by distance.
- Add a third type of user, the **driver**, responsible for picking up dishes from restaurants and delivering them to customers.
  - Use the [geolocation API](https://developer.mozilla.org/en-US/docs/Web/API/Geolocation_API) to show the driver's location at any given time.
  - Drivers should be able to list restaurants near them that have orders almost ready to be delivered.
  - Drivers should be able to see information for orders they were given, including the customer name, address, and phone.
  - Drivers should be able to set an order's state to 'delivered.'
- Develop a REST API for the front end of the website or third-party services.

If you prefer, you can create your own additional requirements.
