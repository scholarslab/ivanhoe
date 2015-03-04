require 'csv'
require 'date'

module ApplicationHelper

  def dump_table(dump_dir, table_name)
    output  = "#{dump_dir}/#{table_name}.csv"
    results = @cxn[table_name.to_sym].all
    CSV.open(output, 'wb', :write_headers => true) do |csv|
      unless results.empty?
        fields = results.first.keys.sort
        csv << fields
        results.each do |row|
          csv << fields.map { |key| row[key] }
        end
      end
    end
  end

  def db_dump
    now      = Time.new
    dump_dir = "db-dump-#{now.strftime '%Y%m%d-%H%M%S.%L'}"
    Dir.mkdir dump_dir

    @cxn["SHOW TABLES;"]
      .map    { |row| row[:Tables_in_test_ivanhoe] }
      .reject { |table| table.start_with? "copy_wp" }
      .each   { |table| dump_table dump_dir, table }
  end


  def tiny_mce_fill_in(name, args)
    page.execute_script("tinymce.editors[0].setContent('#{args[:with]}')")
  end

  def tiny_mce_fill_in_post_content(name, args)
    page.execute_script("tinymce.editors[0].setContent('#{args[:with]}')")
  end

  def tiny_mce_fill_in_post_rationale(name, args)
    page.execute_script("tinymce.editors[1].setContent('#{args[:with]}')")
  end

  def login
    click_link('Log in')
    fill_in 'Username', with: 'admin'
    fill_in 'Password', with: 'admin'
    click_button 'Log In'
  end

  def login_editor
    click_link('Log in')
    fill_in 'Username', with: 'editor'
    fill_in 'Password', with: 'editor'
    click_button 'Log In'
  end

  # Timings:
  # - 0.552375
  # - 0.516728
  # - 0.551325
  # mean = 0.540142667
  #
  # (From run on Feb 25):
  # - 1.22162
  # - 1.3177
  # - 1.288371
  def make_game(user_login='admin')
    db_dump
    @cxn.transaction do
      # wp_posts = @cxn[:wp_posts]
      # row_id = wp_posts.max(:id) + 1

      # guid  = "#{URL_BASE}/?post_type=ivanhoe_game&p=#{row_id}"
      # guid1 = "#{URL_BASE}/?post_type=ivanhoe_game&p=#{row_id + 1}"
      # post_author = @cxn[:wp_users].first(:user_login => user_login)[:ID]
      post_title = Faker::Lorem.words(rand(2..8)).join(' ')
      post_content = Faker::Lorem.paragraphs(rand(3..10)).join('<p>')
      # now = DateTime.now
      # now_gmt = now

      author = WPDB::User.first(:user_login => user_login)
      WPDB::Post.create(
        :post_title            => post_title,
        :post_content          => post_content,
        :author                => author,
        :post_excerpt          => '',
        :to_ping               => '',
        :pinged                => '',
        :post_content_filtered => '',
        :post_status           => 'publish',
        :post_type             => 'ivanhoe_game',
        :comment_status        => 'closed',
      )

      # wp_posts.insert({
        # :ID                    => row_id + 1,
        # :comment_count         => 0,
        # :comment_status        => 'closed',
        # :guid                  => guid1,
        # :menu_order            => 0,
        # :ping_status           => 'open',
        # :pinged                => '',
        # :post_author           => post_author,
        # :post_content          => post_content,
        # :post_content_filtered => '',
        # :post_date             => now,
        # :post_date_gmt         => now_gmt,
        # :post_excerpt          => '',
        # :post_mime_type        => '',
        # :post_modified         => now,
        # :post_modified_gmt     => now_gmt,
        # :post_name             => post_title.gsub(/ /, '-'),
        # :post_parent           => 0,
        # :post_password         => '',
        # :post_status           => 'publish',
        # :post_title            => post_title,
        # :post_type             => 'ivanhoe_game',
        # :to_ping               => '',
      # })
    end

    db_dump

    # TODO: WP recongizes the game above only when we also create one through
    # the web UI below.

    # click_link 'Make a Game'
    # fill_in 'post_title', :with => Faker::Lorem.words(rand(2..8)).join(' ')
    # tiny_mce_fill_in_post_content('post_content', :with => Faker::Lorem.paragraphs(rand(3..10)).join('<p>'))
    # click_button 'Save'

    slept = sleep 20
    puts "SLEPT FOR #{slept}s."

    db_dump
  end

  def make_role
    click_link('Make a Role!')
    fill_in 'post_title', :with => Faker::Lorem.words(rand(2..4)).join(' ')
    tiny_mce_fill_in_post_content('post_content', :with => Faker::Lorem.paragraphs(rand(3..10)).join('<p>'))
    attach_file('post_thumbnail', 'spec/dumps/puppy.jpg')
    click_button 'Save'
  end

  def make_a_move
    click_button 'Make a Move'
    fill_in 'post_title', :with => Faker::Lorem.words(rand(2..8)).join(' ')
    tiny_mce_fill_in_post_content('post_content', :with => Faker::Lorem.paragraphs(rand(3..10)).join('<p>'))
    tiny_mce_fill_in_post_rationale('post_rationale', :with => Faker::Lorem.paragraphs(rand(3..10)).join('<p>'))
    click_button 'Save'
  end

  def respond_to_move
    click_link('Respond')
    fill_in 'post_title', :with => Faker::Lorem.words(rand(2..8)).join(' ')
    tiny_mce_fill_in_post_content('post_content', :with => Faker::Lorem.paragraphs(rand(3..10)).join('<p>'))
    tiny_mce_fill_in_post_rationale('post_rationale', :with => Faker::Lorem.paragraphs(rand(3..10)).join('<p>'))
    click_button 'Save'
  end

  def main_page_respond_to_move
    first('.new_source').click
    click_button('Respond')
    fill_in 'post_title', :with => Faker::Lorem.words(rand(2..8)).join(' ')
    tiny_mce_fill_in_post_content('post_content', :with => Faker::Lorem.paragraphs(rand(3..10)).join('<p>'))
    tiny_mce_fill_in_post_rationale('post_rationale', :with => Faker::Lorem.paragraphs(rand(3..10)).join('<p>'))
    click_button 'Save'
  end

end
