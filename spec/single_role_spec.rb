require 'spec_helper'

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

def make_game
    click_link 'Make a Game'
    fill_in 'post_title', :with => Faker::Lorem.words(rand(2..8)).join(' ')
    tiny_mce_fill_in_post_content('post_content', :with => Faker::Lorem.paragraphs(rand(3..10)).join('<p>'))
    click_button 'Save'
end

def make_role
    click_link('Make a Role!')
    fill_in 'post_title', :with => Faker::Lorem.words(rand(2..4)).join(' ')
    tiny_mce_fill_in_post_content('post_content', :with => Faker::Lorem.paragraphs(rand(3..10)).join('<p>'))
    attach_file('post_thumbnail', 'spec/dumps/puppy.jpg')
    click_button 'Save'
end

def make_a_move
    click_link 'Make a move'
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

describe 'Make a Role View', :type => :feature, :js => true do

    before(:each) do
        visit(URL_BASE)
        click_link('Games')
        login
        make_game
        first('.game-title a').click
        make_role
        make_a_move
        within('#game-data article.role') do
            first('a').click
        end
    end

    it 'has a linked role title' do
        within('.role') do
            expect(page).to have_selector('h1 a')
        end
    end

    it 'has the role image' do
        within('.role') do
            expect(page).to have_selector('img')
        end
    end

    it 'has a role description' do
      within('.role') do
        expect(page).to have_selector('p')
      end
    end

    it 'has a return to game link' do
      within('.role') do
        expect(page).to have_link('Return to game')
        expect(page).to have_selector('.return-btn')
      end
    end

    it 'has a Moves list' do
        within('.moves') do
            expect(page).to have_content('Moves')
            expect(page).to have_selector('ul')
        end
    end

    it 'has linked move titles' do
      within('.moves') do
        expect(page).to have_selector('li a', :match => :first)
      end
    end

    it 'has a Rationales list' do
        within('.rationales') do
            expect(page).to have_content('Rationales')
            expect(page).to have_selector('ul')
        end
    end

    it 'has linked rationale titles' do
      within('.rationales') do
        expect(page).to have_selector('li a', :match => :first)
        expect(page).to have_content('Journal Entry', :match => :first)
      end
    end

end

