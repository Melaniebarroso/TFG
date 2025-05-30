from django import forms
from .models import BlogPost, MaterialCurso

class BlogPostForm(forms.ModelForm):
    class Meta:
        model = BlogPost
        fields = ['titulo', 'contenido', 'imagen']

class PedidoForm(forms.Form):
    nombre_cliente = forms.CharField(max_length=100, label="Nombre completo")
    email_cliente = forms.EmailField(label="Correo electrónico")
    direccion_envio = forms.CharField(widget=forms.Textarea, label="Dirección de envío")

class MaterialCursoForm(forms.ModelForm):
    class Meta:
        model = MaterialCurso
        fields = ['curso', 'titulo', 'archivo']

from .models import ContactoNewsletter
class ContactoNewsletterForm(forms.ModelForm):
    class Meta:
        model = ContactoNewsletter
        fields = ['nombre', 'email']
        widgets = {
            'nombre': forms.TextInput(attrs={
                'placeholder': 'Tu nombre',
                'class': 'form-control bg-transparent border-primary text-white p-3'
            }),
            'email': forms.EmailInput(attrs={
                'placeholder': 'Tu correo electrónico',
                'class': 'form-control bg-transparent border-primary text-white p-3'
            }),
        }

class ContactForm(forms.Form):
    nombre = forms.CharField(max_length=100, widget=forms.TextInput(attrs={
        'class': 'form-control bg-transparent p-4',
        'placeholder': 'Nombre',
        'required': True,
    }))
    email = forms.EmailField(widget=forms.EmailInput(attrs={
        'class': 'form-control bg-transparent p-4',
        'placeholder': 'Email',
        'required': True,
    }))
    mensaje = forms.CharField(widget=forms.Textarea(attrs={
        'class': 'form-control bg-transparent py-3 px-4',
        'placeholder': 'Mensaje',
        'rows': 5,
        'required': True,
    }))

from .models import Alumno
class AlumnoForm(forms.ModelForm):
    class Meta:
        model = Alumno
        fields = ['nombre', 'apellidos', 'email', 'telefono', 'password']
        widgets = {
            'password': forms.PasswordInput(),
        }
