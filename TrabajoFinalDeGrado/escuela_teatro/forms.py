from django import forms
from .models import BlogPost

class BlogPostForm(forms.ModelForm):
    class Meta:
        model = BlogPost
        fields = ['titulo', 'contenido', 'imagen']

class PedidoForm(forms.Form):
    nombre_cliente = forms.CharField(max_length=100, label="Nombre completo")
    email_cliente = forms.EmailField(label="Correo electrónico")
    direccion_envio = forms.CharField(widget=forms.Textarea, label="Dirección de envío")