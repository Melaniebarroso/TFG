from django.db import models

class Administrador(models.Model):
    nombre = models.CharField(max_length=100)
    correo = models.CharField(unique=True, max_length=100)
    telefono = models.CharField(max_length=15)
    password = models.CharField(max_length=1000)
    fecha_registro = models.DateTimeField(auto_now_add=True)

    class Meta:
        db_table = 'administradores'
    def __str__(self):
        return self.nombre

class Alumno(models.Model):
    nombre = models.CharField(max_length=100)
    apellidos = models.CharField(max_length=100)
    email = models.CharField(unique=True, max_length=100)
    telefono = models.CharField(max_length=15)
    password = models.CharField(max_length=200)
    fecha_registro = models.DateTimeField(auto_now_add=True)

    class Meta:
        db_table = 'alumnos' 
    def __str__(self):
        return f"{self.nombre} {self.apellidos}"


class BlogPost(models.Model):
    titulo = models.CharField(max_length=200)
    contenido = models.TextField()
    imagen = models.ImageField(upload_to='blog_imagenes/')
    fecha_creacion = models.DateTimeField(auto_now_add=True)

    class Meta:
        db_table = 'blog_blogpost' 

    def __str__(self):
        return self.titulo

class Curso(models.Model):
    nombre = models.CharField(max_length=255)
    descripcion = models.CharField(max_length=2000)
    fecha_inicio = models.DateField()
    fecha_fin = models.DateField()
    imagen = models.ImageField(upload_to='curso_imagenes/', blank=True, null=True)

    class Meta:
        db_table = 'cursos'
    def __str__(self):
        return self.nombre

class Inscripcion(models.Model):
    alumno = models.ForeignKey(Alumno, on_delete=models.CASCADE)
    curso = models.ForeignKey(Curso, on_delete=models.CASCADE)
    fecha_inscripcion = models.DateTimeField()
    estado = models.CharField(max_length=20)

    class Meta:
        db_table = 'inscripciones'
    def __str__(self):
        return f"{self.alumno} → {self.curso}"

class Producto(models.Model):
    nombre = models.CharField(max_length=50)
    descripcion = models.TextField()
    precio = models.DecimalField(max_digits=10, decimal_places=2)
    stock = models.IntegerField()
    categoria = models.CharField(max_length=100)

    class Meta:
        db_table = 'productos'
    def __str__(self):
        return self.nombre

class ImagenProducto(models.Model):
    producto = models.ForeignKey(Producto, related_name='imagenes', on_delete=models.CASCADE)
    imagen = models.ImageField(upload_to='productos_imagenes/')
    descripcion = models.CharField(max_length=255, blank=True)
    class Meta:
        db_table = 'imagen_producto'
    def __str__(self):
        return f"Imagen de {self.producto.nombre}"


class Pedido(models.Model):
    nombre_cliente = models.CharField(max_length=100)
    email_cliente = models.CharField(max_length=100)
    direccion_envio = models.TextField()
    fecha = models.DateTimeField()
    estado = models.CharField(max_length=50)

    class Meta:
        db_table = 'pedidos'
    def __str__(self):
        return f"Pedido #{self.id} - {self.nombre_cliente}"


class DetallePedido(models.Model):
    pedido = models.ForeignKey(Pedido, on_delete=models.CASCADE)
    producto = models.ForeignKey(Producto, on_delete=models.CASCADE)
    cantidad = models.IntegerField()
    precio_unitario = models.DecimalField(max_digits=10, decimal_places=2)

    class Meta:
        db_table = 'detalles_pedido'
    def __str__(self):
        return f"{self.producto} x{self.cantidad}"


class Espectaculo(models.Model):
    titulo = models.CharField(max_length=150)
    descripcion = models.TextField()
    duracion = models.CharField(max_length=30)


    class Meta:
        db_table = 'espectaculos'
    def __str__(self):
        return self.titulo


class ContactoNewsletter(models.Model):
    email = models.CharField(max_length=100)
    nombre = models.CharField(max_length=100)
    fecha_suscripcion = models.DateTimeField()

    class Meta:
        db_table = 'contactos_newsletter'
    def __str__(self):
        return self.email


class ContactoFormulario(models.Model):
    nombre = models.CharField(max_length=100)
    email = models.CharField(max_length=100)
    mensaje = models.TextField()
    fecha_envio = models.DateTimeField()

    class Meta:
        db_table = 'contactos_formulario'
    def __str__(self):
        return f"{self.nombre} - {self.email}"
