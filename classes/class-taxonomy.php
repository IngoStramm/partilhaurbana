<?php

/**
 * PU_Taxonomy class.
 *
 * Built Custom Taxonomies.
 *
 * @package  Odin
 * @category Taxonomy
 * @author   WPBrasil
 * @version  2.1.4
 */
class PU_Taxonomy
{

    /**
     * Array of labels for the Taxonomy.
     *
     * @var array
     */
    protected $labels = array();

    /**
     * Taxonomy arguments.
     *
     * @var array
     */
    protected $arguments = array();

    /**
     * name
     *
     * @var string
     */
    protected $name = '';

    /**
     * slug
     *
     * @var string
     */
    protected $slug = '';

    /**
     * object_type
     *
     * @var string
     */
    protected $object_type = '';

    /**
     * Construct Taxonomy.
     *
     * @param string $name        The singular name of the taxonomy.
     * @param string $slug        Taxonomy slug.
     * @param string $object_type Name of the object type for the taxonomy object.
     */
    public function __construct($name, $slug, $object_type)
    {
        $this->name        = $name;
        $this->slug        = $slug;
        $this->object_type = $object_type;

        // Register Taxonomy.
        add_action('init', array(&$this, 'register_taxonomy'));
    }

    /**
     * Set custom labels.
     *
     * @param array $labels Custom labels.
     */
    public function set_labels($labels = array())
    {
        $this->labels = $labels;
    }

    /**
     * Set custom arguments.
     *
     * @param array $arguments Custom arguments.
     */
    public function set_arguments($arguments = array())
    {
        $this->arguments = $arguments;
    }

    /**
     * Define Taxonomy labels.
     *
     * @return array Taxonomy labels.
     */
    protected function labels()
    {
        $default = array(
            'name'                       => sprintf(__('%ss', 'mi'), $this->name),
            'singular_name'              => sprintf(__('%s', 'mi'), $this->name),
            'add_or_remove_items'        => sprintf(__('Adicionar ou remover %ss', 'mi'), $this->name),
            'view_item'                  => sprintf(__('Ver %s', 'mi'), $this->name),
            'edit_item'                  => sprintf(__('Editar %s', 'mi'), $this->name),
            'search_items'               => sprintf(__('Pesquisar %s', 'mi'), $this->name),
            'update_item'                => sprintf(__('Atualizar %s', 'mi'), $this->name),
            'parent_item'                => sprintf(__('%s pai:', 'mi'), $this->name),
            'parent_item_colon'          => sprintf(__('%s pai:', 'mi'), $this->name),
            'menu_name'                  => sprintf(__('%ss', 'mi'), $this->name),
            'add_new_item'               => sprintf(__('Adicionar novo %s', 'mi'), $this->name),
            'new_item_name'              => sprintf(__('Novo %s', 'mi'), $this->name),
            'all_items'                  => sprintf(__('Todos %ss', 'mi'), $this->name),
            'separate_items_with_commas' => sprintf(__('%ss separados por vírgula', 'mi'), $this->name),
            'choose_from_most_used'      => sprintf(__('Escolha a partir dos %ss mais usados', 'mi'), $this->name)
        );

        return array_merge($default, $this->labels);
    }

    /**
     * Define Taxonomy arguments.
     *
     * @return array Taxonomy arguments.
     */
    protected function arguments()
    {
        $default = array(
            'labels'            => $this->labels(),
            'hierarchical'      => true, // Like categories.
            'public'            => true,
            'show_ui'           => true,
            'show_admin_column' => true,
            'show_in_nav_menus' => true,
            'show_tagcloud'     => true,
        );

        return array_merge($default, $this->arguments);
    }

    /**
     * Register Taxonomy.
     */
    public function register_taxonomy()
    {
        register_taxonomy($this->slug, $this->object_type, $this->arguments());
    }
}
