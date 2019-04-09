const express = require('express')
const app = express()
const PORT = process.env.PORT || 3000

const session = require('express-session')

app.use(express.urlencoded({ extended: false }))
app.use(session(
    {
        secret: 'secretcode!#$!@!@#$@',
        resave: false,
        saveUninitialized: false
    }
))

app.set('view engine', 'ejs')
app.set('views', './views')
app.use('/public', express.static(__dirname + '/public'))

const database = require('./database.js')
database.startDBandApp(app, PORT)

const flash = require('connect-flash')
app.use(flash())

const passConfig = require('./passConfig.js')
passConfig.config(app)

app.get('/', (req, res) => {
    app.locals.categoriesCollection.find({}).toArray()
        .then(categories => {
            res.render('products', { categories, loggedin: req.user ? true : false })
        })
        .catch(error => {
            res.render('admin/errorPage', { message: 'categories loading error' + error })
        })
})

app.post('/products/category', (req, res) => {
    app.locals.productsCollection.find({ category: req.body.category }).toArray()
        .then(products => {
            console.log(products)
            res.render('category', { category: req.body.category, products, loggedin: req.user ? true : false })
        })
        .catch(error => {
            res.render('admin/errorPage', { message: '/admin/home image loading error' + error })
        })
})

app.get('/aboutus', (req, res) => {
    let artists = [
        {
            name: "Spongebob",
            description: "Completely crazy.",
            image: "Spongebob.jpg"
        },
        {
            name: "Patrick",
            description: "Even more completely crazy.",
            image: "patrick.png"
        },
        {
            name: "Squidward",
            description: "The only normal one.",
            image: "squidward.jpg"
        }
    ]
    res.render('aboutus', { artists, loggedin: req.user ? true : false })
})

app.get('/login', (req, res) => {
    res.render('login', { flash_message: req.flash('flash_message') })
})

app.post('/login', passConfig.passport.authenticate(
    'localLogin',
    {
        successRedirect: '/profile',
        failureRedirect: '/login',
        failureFlash: true
    }
))

app.get('/signup', (req, res) => {
    res.render('signup', { flash_message: req.flash('flash_message') })
})

app.post('/signup', passConfig.passport.authenticate(
    'signupStrategy',
    {
        successRedirect: '/login',
        failureRedirect: '/signup',
        failureFlash: true
    }
))

app.get('/profile', auth, (req, res) => {
    res.render('profile', { user: req.user, loggedin: req.user ? true : false })
})

app.get('/logout', (req, res) => {
    req.logout()
    res.redirect('/')
})

app.get('/admin/home', authAsAdmin, (req, res) => {
    app.locals.imageCollection.find({}).toArray()
        .then(images => {
            res.render('admin/adminhome', { images })
        })
        .catch(error => {
            res.render('admin/errorPage', { message: '/admin/home image loading error' + error })
        })
})

app.get('/admin/imageUpload', authAsAdmin, (req, res) => {
    res.render('admin/imageUpload')
})

const multer = require('multer')
const path = require('path')

const MAX_FILESIZE = 1024 * 1024 * 1
const fileTypes = /jpeg|jpg|png|gif/

const storageOptions = multer.diskStorage({
    destination: (req, file, callback) => {
        callback(null, './public/images')
    },
    filename: (req, file, callback) => {
        callback(null, 'image' + Date.now() + path.extname(file.originalname))
    }
})

const imageUpload = multer({
    storage: storageOptions,
    limits: { fileSize: MAX_FILESIZE },
    fileFilter: (req, file, callback) => {
        const ext = fileTypes.test(path.extname(file.originalname).toLowerCase())
        const mimetype = fileTypes.test(file.mimetype)
        if (ext && mimetype) {
            return callback(null, true)
        } else {
            return callback('Error: Images (jpeg, jpg, png, gif) only')
        }
    }
}).single('imageButton')

app.post('/admin/imageUpload', authAsAdmin, (req, res) => {
    imageUpload(req, res, error => {
        if (error) {
            return res.render('admin/errorPage', { message: error })
        } else if (!req.file) {
            return res.render('admin/errorPage', { message: 'No file selected' })
        }

        // upload success. Save file in to DB
        const image =
        {
            filename: req.file.filename,
            size: req.file.size
        }
        app.locals.imageCollection.insertOne(image)
            .then(result => {
                res.redirect('/admin/home')
            })
            .catch(error => {
                res.render('errorPage', { message: 'image upload DB error' })
            })
    })
})

const fs = require('fs')

app.post('/admin/deleteImage', authAsAdmin, (req, res) => {
    app.locals.imageCollection.deleteOne({ _id: app.locals.ObjectID(req.body._id) })
        .then(result => {
            const filename = req.body.filename
            fs.unlink('./public/images/' + filename, (error) => {
                if (error) {
                    res.render('admin/errorPage', { message: 'fs.unlink error to delete image file' })
                } else {
                    res.redirect('/admin/home')
                }
            })
        })
        .catch(error => {
            res.render('admin/errorPage', { message: 'image DB delete error' })
        })
})

app.get('/admin/manageUsers', authAsAdmin, (req, res) => {
    app.locals.customerCollection.find({}).toArray()
        .then(users => {
            res.render('admin/manageUsers', { users })
        })
        .catch(error => {
            res.render('admin/errorPage', { message: 'manageUser error' })
        })
})

app.post('/admin/deleteUser', authAsAdmin, (req, res) => {
    app.locals.customerCollection.deleteOne({ _id: app.locals.ObjectID(req.body._id) })
        .then(result => {
            res.redirect('/admin/manageUsers')
        })
        .catch(error => {
            res.render('admin/errorPage', { message: 'user DB delete error' })
        })
})

function authAsAdmin(req, res, next) {
    const user = req.user
    if (!user || !user.admin) {
        res.render('401')
    } else {
        next()
    }
}

function auth(req, res, next) {
    const user = req.user
    if (!user) {
        res.render('401')
    } else {
        next()
    }
}