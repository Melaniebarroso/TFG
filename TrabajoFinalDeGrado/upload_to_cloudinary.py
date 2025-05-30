import os
import cloudinary
import cloudinary.uploader
import django

os.environ.setdefault('DJANGO_SETTINGS_MODULE', 'TrabajoFinalDeGrado.settings')
django.setup()

from escuela_teatro.models import (
    Administrador, Alumno, Pedido, Inscripcion, MaterialCurso, DetallePedido,
    Producto, Curso, Espectaculo, BlogPost, ContactoFormulario
)

cloudinary.config(
    cloud_name=os.environ.get('CLOUDINARY_CLOUD_NAME'),
    api_key=os.environ.get('CLOUDINARY_API_KEY'),
    api_secret=os.environ.get('CLOUDINARY_API_SECRET'),
)

MEDIA_ROOT = os.path.join(os.path.dirname(os.path.abspath(__file__)), 'media')

MODELOS_Y_CAMPOS = {
    Administrador: ['imagen'],          
    Alumno: ['foto'],               
    Pedido: ['archivo'],          
    Inscripcion: ['documento'],
    MaterialCurso: ['archivo_material'],
    DetallePedido: ['comprobante'],
    Producto: ['imagen'],
    Curso: ['imagen'],
    Espectaculo: ['imagen'],
    BlogPost: ['imagen'],
    ContactoFormulario: ['archivo_adjunto'],
}

def upload_files():
    for modelo, campos in MODELOS_Y_CAMPOS.items():
        print(f"Procesando modelo {modelo.__name__}")
        objetos = modelo.objects.all()
        for obj in objetos:
            for campo in campos:
                file_field = getattr(obj, campo, None)
                if file_field and hasattr(file_field, 'name') and file_field.name:
                    local_path = os.path.join(MEDIA_ROOT, file_field.name)
                    if os.path.exists(local_path):
                        print(f"Subiendo {local_path} para {modelo.__name__} id={obj.pk} campo={campo}...")
                        resultado = cloudinary.uploader.upload(local_path)
                        setattr(obj, campo, resultado['secure_url'])
                        obj.save()
                        print(f"Actualizado {modelo.__name__} id={obj.pk}")
                    else:
                        print(f"Archivo no encontrado: {local_path}")

if __name__ == '__main__':
    upload_files()
