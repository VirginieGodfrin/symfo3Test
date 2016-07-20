<?php

namespace Test\BlogBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

//form type
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use Test\BlogBundle\Repository\CategoryRepository;

//preSetData
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;





class AdvertType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $pattern = 'S%';
        $builder
            ->add('title', TextType::class)
            ->add('date', dateTimeType::class)
            ->add('author', TextType::class)
            ->add('content', TextareaType::class)
            //Pre_set_data
            //->add('published', CheckboxType::class, array('required'=>false)) 
            //imbrication de form oneToOne, manyToOne
            ->add('image', ImageType::class)
            //imbrication de form ManyToOne ou ManyToMany
            /*
            *->add('categories', CollectionType::class, array( //nom du champ et type du champ 
            *   'entry_type'   => CategoryType::class, // tableau d'option du champ
            *   'allow_add'    => true,
            *   'allow_delete' => true
            *   ))
            */

            //méthode pour n'appelé que les catégories commençant par s
            ->add('categories', EntityType::class, array(
                'class' => 'TestBlogBundle:Category',
                'choice_label'=>'name',
                'expanded'=>true,
                'multiple'=>true,
                'query_builder' => function(CategoryRepository $repository)use($pattern){
                    return $repository->getLikeQueryBuilder($pattern);
                }
            ))

            ->add('save', SubmitType::class);

            $builder->addEventlistener(FormEvents::PRE_SET_DATA, function(FormEvent $event){
                $advert = $event->getData();

                if(null === $advert){
                    return;
                }

                if(!$advert->getPublished()|| null === $advert->getId()) {
                    $event->getForm()->add('published', CheckboxType::class, array('required'=>false));
                }else{
                    $event->getForm()->remove('published');
                }

            });
            
            
        
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Test\BlogBundle\Entity\Advert'
        ));
    }
}
