from django.db import models

class Administrador(models.Model):
    nombre = models.CharField(max_length=100)
    correo = models.CharField(unique=True, max_length=100)
    telefono = models.CharField(max_length=15)
    password = models.CharField(max_length=1000)
    fecha_registro = models.DateTimeField(auto_now_add=True)

    class Meta:
        db_table = 'administradores'

class Alumno(models.Model):
    nombre = models.CharField(max_length=100)
    apellidos = models.CharField(max_length=100)
    email = models.CharField(unique=True, max_length=100)
    telefono = models.CharField(max_length=15)
    password = models.CharField(max_length=200)
    fecha_registro = models.DateTimeField(auto_now_add=True)

    class Meta:
        db_table = 'alumnos' 
