const mongodb = require('mongodb')
const MongoClient = mongodb.MongoClient
const ObjectID = mongodb.ObjectID
const username = 'user'
const password = 'password'
const dbName = 'PrairieDragonCrafts'
const dbHost = 'localhost'
const dbPort = 27017
const collectionName = 'customers'

const dbUrl = `mongodb://${username}:${password}@${dbHost}:${dbPort}?authSource=${dbName}`

let dbclient
let customersCollection

function startDBandApp(app, PORT) {
    MongoClient.connect(dbUrl, {poolSize:30, useNewUrlParser: true})
        .then(client => {
            dbclient = client
            customersCollection = client.db(dbName).collection(collectionName)
            app.locals.customersCollection = customersCollection
            app.locals.categoriesCollection = client.db(dbName).collection('categories')
            app.locals.productsCollection = client.db(dbName).collection('products')
            app.locals.ObjectID = ObjectID
            app.listen(PORT, () => {
                console.log(`Server is running at port ${PORT}`)
            })
        })
        .catch(error => {
            console.log('db connection error:', error)
        })
}

process.on('SIGINT', () => {
    dbclient.close()
    console.log('db connection close by SIGINT')
    process.exit()
})

module.exports= {startDBandApp, ObjectID, customersCollection}