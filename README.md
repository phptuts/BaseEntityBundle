<h1>Symfony Base Entity Bundle</h1>

<h2>Installation Instructions</h2>


<p>This bundle contains two entities.  One is a base entity which is abstract class that you can use for all entities.  It has a primary key with column name id that auto increments.  The we have createdAt and updateAt which use lifecycle callbacks to update the entity everytime is it peristed or updated</p>

<p>The next entity is the user entity that implements the AdvancedUserInterface and allows you to quickly build a user class that will allow you to quickly build an authenicated system. <p>
