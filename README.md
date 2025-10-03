# Portfolio Website - API Builder

# Project Overview

**API Builder** is a simple web application that allows users to quickly generate backend APIs through an intuitive user interface.

Using a straightforward form, you can:
- Create a data model with a name and columns
- Specify column types and attributes like `required` or `unique`
- Automatically generate the corresponding controller
- Create the necessary database tables based on your model

The goal of this project is to simplify the process of setting up basic backend structures. It generates the PHP files based on your input, but you can still add or edit functions manually.  
If you want to use a newly written function of a controller, you will need to add the route in `server/src/Routes/routes_cache.php`.

---

# Setup for the project

Git clone the project from the develop branch. This is the most up to date version.
Setup the project using Docker Compose: docker-compose up and make sure to copy the `.env-example` into a `.env` file. This sets the variables for your environment.

---

# Setup for Frontend

See the `client/README.md` file for instructions on setting up the frontend.

---

# Setup for Backend

docker-compose run --rm backend composer install
