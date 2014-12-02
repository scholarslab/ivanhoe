require 'spec_helper'

def tiny_mce_fill_in(name, args)
  page.execute_script("tinymce.editors[0].setContent('#{args[:with]}')")
end

def login
    click_link('Log in')
    fill_in 'Username', with: 'admin'
    fill_in 'Password', with: 'admin'
    click_button 'Log In'
end

def make_game
    click_link 'Make a Game'
    fill_in 'post_title', :with => Faker::Lorem.words(rand(2..8)).join(' ')
    tiny_mce_fill_in('post_content', :with => Faker::Lorem.paragraphs(rand(3..10)).join('<p>'))
    click_button 'Save'
end

def make_role
    click_link('Make a Role!')
    fill_in 'post_title', :with => Faker::Lorem.words(rand(2..4)).join(' ')
    tiny_mce_fill_in('post_content', :with => Faker::Lorem.paragraphs(rand(3..10)).join('<p>'))
    click_button 'Save'
end

def make_a_move
    click_link 'Make a move'
    fill_in 'post_title', :with => Faker::Lorem.words(rand(2..8)).join(' ')
    tiny_mce_fill_in('post_content', :with => Faker::Lorem.paragraphs(rand(3..10)).join('<p>'))
    tiny_mce_fill_in('post_rationale', :with => Faker::Lorem.paragraphs(rand(3..10)).join('<p>'))
    click_button 'Save'
end

def respond_to_move
    click_link('Respond')
    fill_in 'post_title', :with => Faker::Lorem.words(rand(2..8)).join(' ')
    tiny_mce_fill_in('post_content', :with => Faker::Lorem.paragraphs(rand(3..10)).join('<p>'))
    tiny_mce_fill_in('post_rationale', :with => Faker::Lorem.paragraphs(rand(3..10)).join('<p>'))
    click_button 'Save'
end
